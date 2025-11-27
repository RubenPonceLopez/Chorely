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
     * Index: lista calendarios del usuario.
     *
     * IMPORTANTE: Excluye calendarios cuyo nombre empieza por "Clonado - "
     * para que los calendarios creados mediante clonación no aparezcan
     * en la lista principal (solución no destructiva).
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user ? $user->id : 0;

        $calendars = Calendar::with(['flat'])
            ->where(function($q) use ($userId) {
                // Calendarios creados por el usuario o donde el usuario es miembro del piso
                $q->where('created_by', $userId)
                  ->orWhereHas('flat.members', function($sub) use ($userId) {
                      $sub->where('user_id', $userId);
                  });
            })
            // Excluir calendarios clonados marcados por nombre
            ->where(function($q) {
                $q->whereNull('name')->orWhere('name', 'not like', 'Clonado - %');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('calendars.index', compact('calendars'));
    }

    /**
     * Mostrar formulario crear calendario.
     */
    public function create()
    {
        $flats = Flat::all();
        $tasks = Task::all();
        $users = User::all();
        return view('calendars.create', compact('flats','tasks','users'));
    }

    /**
     * Store: crea calendario + tareas por defecto + participantes.
     * (mantengo tu implementación previa, ligeramente formateada)
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

            $monthStartDate = Carbon::createFromFormat('Y-m', $data['month_start'])->startOfMonth();

            $calendar = Calendar::create([
                'flat_id' => $flat->id,
                'name' => $data['name'],
                'month_start' => $monthStartDate->format('Y-m-d'),
                'created_by' => $user->id,
            ]);

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

            foreach ($data['participants'] ?? [] as $pName) {
                $pName = trim($pName);
                if (!$pName) continue;

                $user = User::firstOrCreate(
                    ['name' => $pName],
                    [
                        'apellido' => '',
                        'email' => Str::slug($pName).'+'.time().'@local.test',
                        'password' => Hash::make(Str::random(12))
                    ]
                );

                FlatMember::firstOrCreate([
                    'flat_id' => $flat->id,
                    'user_id' => $user->id,
                ], ['role' => 'member']);
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
     * show: renderiza la vista del calendario (mantengo tu implementación).
     */
    public function show(Calendar $calendar)
    {
        if (!$calendar->flat) {
            return redirect()->route('calendars.index')
                ->with('error', 'Este calendario no tiene un piso asociado.');
        }

        $flatMembers = $calendar->flat->members()->with('user')->get();

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
