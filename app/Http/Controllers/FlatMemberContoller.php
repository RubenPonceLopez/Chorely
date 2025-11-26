<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flat;
use App\Models\User;

class FlatMemberController extends Controller
{
    public function store(Request $request, Flat $flat)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|string|max:50'
        ]);

        // Evitar duplicados
        if ($flat->users()->where('user_id', $data['user_id'])->exists()) {
            return back()->withErrors(['user_id' => 'Usuario ya es miembro.']);
        }

        $flat->users()->attach($data['user_id'], ['role' => $data['role'] ?? 'member']);

        return back()->with('success', 'Miembro añadido.');
    }

    public function destroy(Flat $flat, $memberId)
    {
        $flat->users()->detach($memberId);
        return back()->with('success', 'Miembro eliminado.');
    }
}
