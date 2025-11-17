<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * EventController: legacy placeholder kept for compatibility.
 * All event routes are handled by CalendarEventController.
 * This controller returns a 404 to avoid accidental usage.
 */
class EventController extends Controller
{
    public function index(Request $request)
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }
}
