<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\FlatMember;
use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\Flat;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Index: lista de calendarios del usuario.
     *
     * IMPORTANTE: Excluye calendarios cuyo nombre empieza por "Clonado - "
     * para que los calendarios creados mediante clonación no aparezcan
     * en la lista principal (solución no destructiva).
     *
     * Ahora devuelve un Paginator (->paginate) para poder usar ->links() en la vista.
     */
    public function index(Request $request)
    {
        // Aseguramos usuario (normalmente las rutas están protegidas por auth middleware)
        $user = Auth::user();
        if (! $user) {
            // Si no está autenticado, redirigimos al login (o podemos devolver un paginator vacío)
            return redirect()->route('login');
        }
        $userId = $user->id;

        $perPage = 12; // ajustar número de elementos por página si quieres

        $calendarsQuery = Calendar::with(['flat'])
            ->where(function($q) use ($userId) {
                // Calendarios creados por el usuario o donde el usuario es miembro del piso
                $q->where('created_by', $userId)
                  ->orWhereHas('flat.members', function($sub) use ($userId) {
                      $sub->where('user_id', $userId);
                  });
            })
            // Excluir calendarios clonados marcados por nombre (nombre nulo o que no empiece por "Clonado - ")
            ->where(function($q) {
                $q->whereNull('name')
                  ->orWhere('name', 'not like', 'Clonado - %');
            })
            ->orderBy('created_at', 'desc');

        $calendars = $calendarsQuery->paginate($perPage)->withQueryString();

        return view('calendars.index', compact('calendars'));
    }

    /**
     * Mostrar formulario crear calendario.
     */
    public function create()
    {
        // Si tu vista create necesita flats/tasks/users, se los pasamos;
        // si no los usas en la vista, puedes quitar las consultas.
        $flats = Flat::all();
        $tasks = Task::all();
        $users = User::all();
        return view('calendars.create', compact('flats','tasks','users'));
    }

    /**
     * Store: crea calendario + tareas por defecto + participantes.
     * Recibe JSON desde la vista y devuelve JSON con redirect.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'flat_name' => 'required|string|max:150',
            'month_start' => 'required|string',
            'participants' => 'nullable|array',
            'participants.*' => 'nullable|string|max:255'
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Usuario no autenticado.'], 401);
        }

        DB::beginTransaction();
        try {
            $flat = Flat::firstOrCreate(
                ['name' => $data['flat_name']],
                ['description' => null]
            );

            // month_start esperado YYYY-MM (input type="month")
            try {
                $monthStartDate = Carbon::createFromFormat('Y-m', $data['month_start'])->startOfMonth();
            } catch (\Exception $e) {
                // intentar parsear si se pasa YYYY-MM-DD
                $monthStartDate = Carbon::parse($data['month_start'])->startOfMonth();
            }

            $calendar = Calendar::create([
                'flat_id' => $flat->id,
                'name' => $data['name'],
                'month_start' => $monthStartDate->format('Y-m-d'),
                'created_by' => $user->id,
            ]);

            // Tareas por defecto: crear solo si no existen para ese flat
            $defaultTasks = [
                'Limpiar los baños',
                'Sacar la basura',
                'Fregar los platos',
                'Limpiar la cocina',
                'Barrer la casa',
                'Hacer las habitaciones',
            ];

            $existingTaskNames = Task::where('flat_id', $flat->id)
                ->pluck('name')
                ->map(fn($n) => mb_strtolower(trim($n)))
                ->toArray();

            foreach ($defaultTasks as $taskName) {
                if (!in_array(mb_strtolower($taskName), $existingTaskNames)) {
                    Task::create([
                        'flat_id' => $flat->id,
                        'name' => $taskName,
                        'description' => null,
                        'created_at' => Carbon::now()
                    ]);
                }
            }

            // Participantes: crear usuario de prueba si no existe y linkear al flat
            foreach ($data['participants'] ?? [] as $pName) {
                $pName = trim($pName);
                if (!$pName) continue;

                $userCreated = User::firstOrCreate(
                    ['name' => $pName],
                    [
                        'apellido' => '',
                        // email unico temporal
                        'email' => Str::slug($pName).'+'.time().'@local.test',
                        'password' => Hash::make(Str::random(12))
                    ]
                );

                FlatMember::firstOrCreate(
                    [
                        'flat_id' => $flat->id,
                        'user_id' => $userCreated->id,
                    ],
                    ['role' => 'member']
                );
            }

            DB::commit();

            return response()->json([
                'ok' => true,
                'calendar_id' => $calendar->id,
                'redirect' => route('calendars.show', $calendar->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creando calendario: '.$e->getMessage());
            return response()->json(['ok' => false, 'message' => 'Error interno al crear calendario.'], 500);
        }
    }

    /**
     * GET /api/calendar/get-or-create
     * Params: flat_id, year, month
     * Devuelve: { ok: true, calendar_id: int, created: bool, month_start: 'YYYY-MM-DD' }
     * Si no existe calendario para ese piso/mes lo crea.
     */
    public function getOrCreateForMonth(Request $request)
    {
        $data = $request->validate([
            'flat_id' => 'required|integer|exists:flats,id',
            'year'    => 'required|integer',
            'month'   => 'required|integer|min:1|max:12'
        ]);

        $userId = $request->user()?->id ?? null;

        $flat = Flat::findOrFail($data['flat_id']);

        // autorización: el usuario debe ser miembro o creador
        $isMember = false;
        if ($userId) {
            $isMember = ($flat->members()->where('user_id', $userId)->exists())
                        || (isset($flat->created_by) && $flat->created_by == $userId);
        }

        if (! $isMember) {
            return response()->json(['ok' => false, 'message' => 'No autorizado.'], 403);
        }

        $monthStart = Carbon::create($data['year'], $data['month'], 1)->startOfMonth()->format('Y-m-d');

        $calendar = Calendar::where('flat_id', $data['flat_id'])
                    ->where('month_start', $monthStart)
                    ->first();

        $created = false;
        if (! $calendar) {
            $calendar = Calendar::create([
                'flat_id' => $data['flat_id'],
                'name' => 'Auto - ' . Carbon::parse($monthStart)->format('F Y'),
                'month_start' => $monthStart,
                'created_by' => $userId ?? null
            ]);
            $created = true;
        }

        return response()->json([
            'ok' => true,
            'calendar_id' => $calendar->id,
            'created' => $created,
            'month_start' => $calendar->month_start
        ]);
    }

    /**
     * show: renderiza la vista del calendario.
     * Se asegura de proporcionar las variables que la vista espera:
     * - flatMembers (colección con relación user)
     * - tasks (colección de tareas del flat)
     */
    public function show(Calendar $calendar)
    {
        if (!$calendar->flat) {
            return redirect()->route('calendars.index')
                ->with('error', 'Este calendario no tiene un piso asociado.');
        }

        // cargamos miembros con su usuario
        $flatMembers = $calendar->flat->members()->with('user')->get();

        // si no hay tareas, creamos las por defecto (como tenías antes)
        $existingCount = Task::where('flat_id', $calendar->flat_id)->count();
        if ($existingCount === 0) {
            $defaultTasks = [
                'Limpiar los baños',
                'Sacar la basura',
                'Fregar los platos',
                'Limpiar la cocina',
                'Barrer la casa',
                'Hacer las habitaciones',
            ];

            foreach ($defaultTasks as $taskName) {
                Task::create([
                    'flat_id' => $calendar->flat_id,
                    'name' => $taskName,
                    'description' => null,
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        $tasks = Task::where('flat_id', $calendar->flat_id)->get();

        return view('calendars.show', compact('calendar', 'flatMembers', 'tasks'));
    }
}
