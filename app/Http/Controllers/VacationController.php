<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flat;
use App\Models\Vacation;

class VacationController extends Controller
{
    public function store(Request $request, Flat $flat)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'year' => 'required|integer',
            'week_number' => 'required|integer',
            'note' => 'nullable|string',
        ]);

        $data['flat_id'] = $flat->id;

        Vacation::create($data);

        return back()->with('success', 'Vacaciones registradas.');
    }
}
