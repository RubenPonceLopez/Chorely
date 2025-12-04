<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\User;
use App\Models\FlatMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlatMemberController extends Controller
{
    /**
     * Devuelve los miembros del piso en JSON.
     * Usado por calendar-chorely.js cuando carga FLAT_MEMBERS.
     */
    public function index(Request $request)
    {
        $flatId = (int) $request->query('flat_id', 1);

        $members = FlatMember::with('user')
            ->where('flat_id', $flatId)
            ->get()
            ->map(function($fm) {
                $u = $fm->user;

                return [
                    'id'   => $u->id,
                    'name' => trim($u->name . ' ' . $u->apellido)
                ];
            });

        return response()->json($members);
    }

    /**
     * Añadir miembro al flat (uso interno en la gestión del piso).
     */
    public function store(Request $request, Flat $flat)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role'    => 'nullable|string|max:50'
        ]);

        if ($flat->users()->where('user_id', $data['user_id'])->exists()) {
            return back()->withErrors(['user_id' => 'Este usuario ya es miembro del piso.']);
        }

        $flat->users()->attach($data['user_id'], [
            'role' => $data['role'] ?? 'member'
        ]);

        return back()->with('success', 'Miembro añadido correctamente.');
    }

    /**
     * Eliminar miembro del flat.
     */
    public function destroy(Flat $flat, $memberId)
    {
        try {
            $flat->users()->detach($memberId);
        } catch (\Exception $e) {
            Log::warning("No se pudo eliminar miembro {$memberId}: ".$e->getMessage());
            return back()->withErrors(['member' => 'No se pudo eliminar el miembro.']);
        }

        return back()->with('success', 'Miembro eliminado correctamente.');
    }
}
