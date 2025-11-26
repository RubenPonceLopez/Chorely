<?php

namespace App\Http\Controllers;

use App\Models\FlatMember;
use Illuminate\Http\Request;

class FlatMemberController extends Controller
{
    public function index(Request $request)
    {
        $flatId = (int) $request->query('flat_id', 1);
        $members = FlatMember::with('user')->where('flat_id', $flatId)->get()->map(function($fm) {
            $u = $fm->user;
            return [
                'id' => $u->id,
                'name' => ($u->name ?? '') . (isset($u->apellido) ? ' ' . $u->apellido : '')
            ];
        });

        return response()->json($members);
    }
}
