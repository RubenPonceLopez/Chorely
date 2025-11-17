<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flat;
use App\Models\Assignment;
use App\Models\Task;

class AssignmentController extends Controller
{
    // Lista asignaciones para una semana concreta
    public function week(Flat $flat, $year, $week)
    {
        $assignments = Assignment::with('task','user')
            ->where('flat_id', $flat->id)
            ->where('year', $year)
            ->where('week_number', $week)
            ->get();

        return view('assignments.week', compact('flat','assignments','year','week'));
    }

    // Crear una asignación
    public function store(Request $request, Flat $flat)
    {
        $data = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'year' => 'required|integer',
            'week_number' => 'required|integer',
            'day_of_week' => 'required|string|max:10',
            'assigned_user_id' => 'required|exists:users,id',
        ]);

        $data['flat_id'] = $flat->id;

        // Opcional: comprobar que la tarea pertenece al flat
        $task = Task::findOrFail($data['task_id']);
        if ($task->flat_id != $flat->id) {
            return back()->withErrors(['task_id' => 'La tarea no pertenece a este piso.']);
        }

        Assignment::create($data);

        return back()->with('success', 'Asignación creada.');
    }
}
