<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flat;
use Illuminate\Support\Facades\Auth;

class FlatController extends Controller
{
    public function index(Request $request)
    {
        // Flats en los que está el usuario
        // $request->user() is properly typed as User|null by Laravel
        $flats = $request->user()?->flats()->get() ?? [];
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
        $userId = Auth::id();
        if ($userId) {
            $flat->users()->attach($userId, ['role' => 'owner']);
        }

        return redirect()->route('flats.show', $flat);
    }

    public function show(Flat $flat)
    {
        $flat->load(['users', 'tasks', 'assignments']);
        return view('flats.show', compact('flat'));
    }
}
