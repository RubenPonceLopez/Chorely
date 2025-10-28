<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flat;

class FlatController extends Controller
{
    public function index()
    {
        // Flats en los que está el usuario
        $flats = auth()->user()->flats()->get();
        return view('flats.index', compact('flats'));
    }

    public function create()
    {
        return view('flats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $flat = Flat::create($validated);

        // Añadir al creador como miembro (role: owner)
        $flat->users()->attach(auth()->id(), ['role' => 'owner']);

        return redirect()->route('flats.show', $flat);
    }

    public function show(Flat $flat)
    {
        $flat->load(['users', 'tasks', 'assignments']);
        return view('flats.show', compact('flat'));
    }
}
