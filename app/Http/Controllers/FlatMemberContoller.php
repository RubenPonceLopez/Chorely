<?php

namespace App\Http\Controllers;

use App\Models\FlatMember;

class FlatMemberController extends Controller {
    public function index() {
        // Devuelve usuarios del piso 1 (puedes filtrar por flat logueado)
        return FlatMember::with('user')->where('flat_id',1)->get()->map(fn($fm) => [
            'id' => $fm->user->id,
            'name' => $fm->user->name
        ]);
    }
}
