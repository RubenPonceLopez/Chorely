<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalendarEventController extends Controller {

    /**
     * INDEX - devuelve eventos para FullCalendar
     * FIX: deduplicamos por combinación (calendar_id, task_id, event_date, assigned_user_id)
     *      para evitar que la UI muestre duplicados aunque la BD tenga filas repetidas.
     */
    public function index(Request $request)
{
    $query = CalendarEvent::with('task','assignedUser');

    if ($request->has('calendar_id')) {
        $query->where('calendar_id', $request->query('calendar_id'));
    }

    if ($request->has('start')) {
        $start = date('Y-m-d', strtotime($request->query('start')));
        $query->where('event_date', '>=', $start);
    }
    if ($request->has('end')) {
        $end = date('Y-m-d', strtotime($request->query('end')));
        $query->where('event_date', '<=', $end);
    }

    $events = $query->get();

    $result = [];

    foreach ($events as $ev) {

        // CONVERTIR HORAS A ISO PARA FULLCALENDAR
        $startISO = null;
        $endISO = null;

        if ($ev->event_date && $ev->start_time) {
            $startISO = $ev->event_date . 'T' . substr($ev->start_time,0,5) . ':00';
        }
        if ($ev->event_date && $ev->end_time) {
            $endISO = $ev->event_date . 'T' . substr($ev->end_time,0,5) . ':00';
        }

        $result[] = [
            'id' => $ev->id,
            'title' => ($ev->task ? $ev->task->name : 'Tarea') .
                       ($ev->assignedUser ? ' - ' . $ev->assignedUser->name : ''),

            'start' => $startISO ?? $ev->event_date,
            'end'   => $endISO,

            'allDay' => false,

            'backgroundColor' => $ev->color ?? '#3788d8',

            'extendedProps' => [
                'taskId' => $ev->task_id,
                'usuario' => $ev->assigned_user_id,
                'status' => $ev->status,
                'start_time' => substr($ev->start_time ?? '',0,5),
                'end_time' => substr($ev->end_time ?? '',0,5),
                'color' => $ev->color,
            ]
        ];
    }

    return response()->json($result);
}


    // ================================
    //   STORE (CREAR EVENTO) - con protección contra duplicados
    // ================================
    public function store(Request $request) {

        Log::info("STORE EVENT: ", $request->all()); // <--- ayuda brutal para debug

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

        // Normalizar event_date a Y-m-d
        $data = $request->only([
            'calendar_id','task_id','assigned_user_id','event_date',
            'status','start_time','end_time','notes','color','all_day'
        ]);

        if (!empty($data['event_date'])) {
            try {
                $data['event_date'] = Carbon::parse($data['event_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                // dejamos como vino si no pudo parsearse (se validó antes)
            }
        }

        try {
            // --- PROTECCIÓN: buscar duplicado exacto antes de crear ---
            $q = CalendarEvent::where('calendar_id', $data['calendar_id'])
                ->where('task_id', $data['task_id'])
                ->where('event_date', $data['event_date']);

            if (isset($data['assigned_user_id']) && $data['assigned_user_id'] !== null && $data['assigned_user_id'] !== '') {
                $q->where('assigned_user_id', $data['assigned_user_id']);
            } else {
                // buscar rows que tengan assigned_user_id NULL también
                $q->whereNull('assigned_user_id');
            }

            $existing = $q->first();

            if ($existing) {
                // Si ya existe, devolvemos info del existente en lugar de crear duplicado.
                return response()->json([
                    'id' => $existing->id,
                    'calendar_id' => $existing->calendar_id,
                    'task_id' => $existing->task_id,
                    'assigned_user_id' => $existing->assigned_user_id,
                    'event_date' => $existing->event_date,
                    'status' => $existing->status,
                    'message' => 'Event already exists - returning existing record.'
                ], 200);
            }

            // No existe: creamos
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
