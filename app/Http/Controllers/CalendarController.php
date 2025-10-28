<?php

// app/Http/Controllers/CalendarController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\Flat;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;

class CalendarController extends Controller
{
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
            'flat_id' => 'required|integer|exists:flats,id',
            'name' => 'required|string|max:150',
            'month_start' => 'required|date', // espera YYYY-MM-01 o 'YYYY-MM-01'
            'participants' => 'nullable|array',
            'participants.*' => 'integer|exists:users,id'
        ]);

        $data['created_by'] = $request->user()->id;
        $calendar = Calendar::create($data);

        // opcional: puedes asociar miembros al flat o crear entries en flat_members si vienen nuevos.
        // pero en demo asumimos miembros ya existen en flat_members

        return response()->json([
            'ok' => true,
            'calendar_id' => $calendar->id,
            'redirect' => route('calendars.show', $calendar->id)
        ]);
    }

    public function show(Calendar $calendar)
    {
        // Para la vista del calendario necesitamos: calendar, tasks, miembros del flat
        $tasks = Task::where('flat_id', $calendar->flat_id)->get();
        // miembros:
        $members = $calendar->flat->members()->with('user')->get(); // asume relación
        // Para simplicidad, también trae todos los usuarios del flat:
        $users = User::join('flat_members','users.id','flat_members.user_id')
            ->where('flat_members.flat_id', $calendar->flat_id)
            ->select('users.id','users.name','users.email')->get();

        return view('calendars.show', compact('calendar','tasks','users'));
    }
}