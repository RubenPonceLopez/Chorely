<?php

// app/Http/Controllers/CalendarController.php
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
    public function index()
    {
        // Obtener calendarios del usuario actual (puedes ajustar la lógica según tus necesidades)
        $calendars = Calendar::with(['flat'])
            ->where('created_by', Auth::user()->id)
            ->orWhereHas('flat.members', function($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('calendars.index', compact('calendars'));
    }

    public function create()
    {
        // Trae flats, tasks y usuarios según lo necesites para el form
        $flats = Flat::all();
        $tasks = Task::all();
        // opcional: usuarios (para asignar en form)
        $users = User::all();
        return view('calendars.create', compact('flats','tasks','users'));
    }

   public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:150',
        'flat_name' => 'required|string|max:150',
        'month_start' => 'required|string', // vendrá como "2025-12"
        'participants' => 'nullable|array',
        'participants.*' => 'nullable|string|max:255'
    ]);

    $user = $request->user();
    if (!$user) {
        return response()->json(['ok' => false, 'message' => 'Usuario no autenticado.'], 401);
    }

    DB::beginTransaction();
    try {
        // Crear o buscar el piso
        $flat = Flat::firstOrCreate(
            ['name' => $data['flat_name']],
            ['description' => null]
        );

        // Convertir month_start a fecha real: YYYY-MM-01
        $monthStartDate = Carbon::createFromFormat('Y-m', $data['month_start'])->startOfMonth();

        // Crear calendario
        $calendar = Calendar::create([
            'flat_id' => $flat->id,
            'name' => $data['name'],
            'month_start' => $monthStartDate->format('Y-m-d'),
            'created_by' => $user->id,
        ]);

        // Crear tareas predeterminadas si no existen
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

        // Crear/Asignar participantes
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



    public function show(Calendar $calendar)
    {
        if (!$calendar->flat) {
            return redirect()->route('calendars.index')
                ->with('error', 'Este calendario no tiene un piso asociado.');
        }

        // Obtener miembros del piso con sus usuarios
        $flatMembers = $calendar->flat->members()->with('user')->get();

        // Si no hay tareas en la BBDD para este piso, creamos las tareas por defecto
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

        // Obtener tareas del piso
        $tasks = Task::where('flat_id', $calendar->flat_id)->get();

        return view('calendars.show', compact('calendar', 'flatMembers', 'tasks'));
    }
}