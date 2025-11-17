<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarEventController extends Controller {

    public function index() {
        return CalendarEvent::with('task','assignedUser')->get();
    }

    public function store(Request $request) {
        $event = CalendarEvent::create([
            'calendar_id' => 1,
            'task_id' => $request->task_id,
            'assigned_user_id' => $request->assigned_user_id,
            'event_date' => $request->event_date,
            'status' => $request->status ?? 'pending'
        ]);
        return response()->json($event);
    }

    public function update(Request $request, $id) {
        $event = CalendarEvent::find($id);
        $event->update(['event_date' => $request->event_date]);
        return response()->json($event);
    }

    public function destroy($id) {
        $event = CalendarEvent::find($id);
        $event->delete();
        return response()->json(['success'=>true]);
    }
}