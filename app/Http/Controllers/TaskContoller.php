<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flat;
use App\Models\Task;

class TaskController extends Controller
{
    public function create(Flat $flat)
    {
        return view('tasks.create', compact('flat'));
    }

    public function store(Request $request, Flat $flat)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        // Asegurar unicidad por flat: la DB tiene constraint (uq_tasks_flat_name)
        $data['flat_id'] = $flat->id;
        Task::create($data);

        return redirect()->route('flats.show', $flat)->with('success', 'Tarea creada.');
    }
}
