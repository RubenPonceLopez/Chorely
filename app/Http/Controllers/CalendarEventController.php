<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalendarEventController extends Controller {

    public function index(Request $request) {
        $query = CalendarEvent::with('task','assignedUser');

        if ($request->has('calendar_id')) {
            $query->where('calendar_id', $request->query('calendar_id'));
        }

        // FullCalendar envía start/end en ISO
        if ($request->has('start')) {
            $start = date('Y-m-d', strtotime($request->query('start')));
            $query->where('event_date', '>=', $start);
        }
        if ($request->has('end')) {
            $end = date('Y-m-d', strtotime($request->query('end')));
            $query->where('event_date', '<=', $end);
        }

        $events = $query->get()->map(function($ev) {
            return [
                'id' => $ev->id,
                'title' => ($ev->task ? $ev->task->name : 'Tarea') . ($ev->assignedUser ? ' - ' . ($ev->assignedUser->name ?? '') : ''),
                'start' => $ev->event_date,
                'allDay' => (bool)$ev->all_day,
                'extendedProps' => [
                    'taskId' => $ev->task_id,
                    'usuario' => $ev->assigned_user_id,
                    'status' => $ev->status
                ]
            ];
        });

        return response()->json($events);
    }



    // ================================
    //   STORE (CREAR EVENTO)
    // ================================
    public function store(Request $request) {

        $v = Validator::make($request->all(), [
            'calendar_id' => 'required|integer|exists:calendars,id',
            'task_id' => 'required|integer|exists:tasks,id',
            'assigned_user_id' => 'nullable|integer|exists:users,id',
            'event_date' => 'required|date',
            'status' => 'nullable|string'
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        try {

            // --- Normalizar datos ---
            $data = $request->only([
                'calendar_id','task_id','assigned_user_id','event_date',
                'status','start_time','end_time','notes','color','all_day'
            ]);

            // Convertir event_date a formato Y-m-d para MySQL
            if (!empty($data['event_date'])) {
                try {
                    $data['event_date'] = Carbon::parse($data['event_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning("Fecha no pudo parsearse: ".$data['event_date']);
                }
            }

            $event = CalendarEvent::create($data);

            return response()->json([
                'id' => $event->id,
                'calendar_id' => $event->calendar_id,
                'task_id' => $event->task_id,
                'assigned_user_id' => $event->assigned_user_id,
                'event_date' => $event->event_date,
                'status' => $event->status
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error guardando CalendarEvent: '.$e->getMessage()."\n".$e->getTraceAsString());
            return response()->json(['message' => 'Error interno al crear evento', 'exception' => $e->getMessage()], 500);
        }
    }



    // ================================
    //   UPDATE (ACTUALIZAR EVENTO)
    // ================================
    public function update(Request $request, $id) {

        $event = CalendarEvent::findOrFail($id);

        try {

            $data = $request->only([
                'event_date','assigned_user_id','status',
                'start_time','end_time','notes','color','all_day'
            ]);

            // Normalizar fecha (por si viene en ISO con T...)
            if (!empty($data['event_date'])) {
                try {
                    $data['event_date'] = Carbon::parse($data['event_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning("Fecha inválida en update(): ".$data['event_date']);
                }
            }

            $event->update($data);

            return response()->json(['ok'=>true,'event'=>$event]);

        } catch (\Exception $e) {
            Log::error('CalendarEvent update error: '.$e->getMessage()."\n".$e->getTraceAsString());
            return response()->json(['ok'=>false,'message'=>'Error interno al actualizar evento.'], 500);
        }
    }



    public function destroy($id) {
        $event = CalendarEvent::findOrFail($id);
        $event->delete();
        return response()->json(['success'=>true]);
    }
}
