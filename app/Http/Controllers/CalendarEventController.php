<?php
// app/Http/Controllers/CalendarEventController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\Carbon;

class CalendarEventController extends Controller
{
    // Devuelve resources (miembros) y events (formato FullCalendar)
    public function index(Calendar $calendar, Request $request)
    {
        // resources: miembros del flat
        $users = User::join('flat_members','users.id','flat_members.user_id')
            ->where('flat_members.flat_id', $calendar->flat_id)
            ->select('users.id','users.name','users.apellido')->get()
            ->map(function($u){
                return ['id' => 'u'.$u->id, 'title' => trim($u->name . ' ' . ($u->apellido ?? '')) ];
            });

        // events: calendar_events para el rango (opcional filtro start/end)
        $start = $request->query('start'); // ISO date optional
        $end = $request->query('end');

        $query = CalendarEvent::where('calendar_id', $calendar->id);
        if ($start && $end) {
            $query->whereBetween('event_date', [$start, $end]);
        }

        $events = $query->get()->map(function($e){
            return [
                'id' => $e->id,
                'title' => $e->task ? $e->task->name : ($e->notes ?? 'Tarea'),
                'start' => $e->event_date . 'T' . substr($e->start_time,0,5),
                'end' => $e->event_date . 'T' . substr($e->end_time,0,5),
                'resourceId' => $e->assigned_user_id ? 'u'.$e->assigned_user_id : null,
                'extendedProps' => [
                    'task_id' => $e->task_id,
                    'status' => $e->status,
                    'notes' => $e->notes
                ]
            ];
        });

        return response()->json(['resources' => $users, 'events' => $events]);
    }

    // Crear evento (desde modal)
    public function store(Calendar $calendar, Request $request)
    {
        $data = $request->validate([
            'task_id' => 'nullable|integer|exists:tasks,id',
            'assigned_user_id' => 'nullable|integer|exists:users,id',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'notes' => 'nullable|string'
        ]);

        $ev = CalendarEvent::create(array_merge($data, ['calendar_id' => $calendar->id]));

        return response()->json([
            'ok' => true,
            'event' => [
                'id' => $ev->id,
                'title' => $ev->task ? $ev->task->name : ($ev->notes ?? 'Evento'),
                'start' => $ev->event_date . 'T' . substr($ev->start_time,0,5),
                'end' => $ev->event_date . 'T' . substr($ev->end_time,0,5),
                'resourceId' => $ev->assigned_user_id ? 'u'.$ev->assigned_user_id : null,
            ]
        ]);
    }

    // Actualizar (mover fecha/hora o marcar done)
    public function update(Request $request, CalendarEvent $event)
    {
        $data = $request->validate([
            'event_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'assigned_user_id' => 'nullable|integer|exists:users,id',
            'status' => 'nullable|in:pending,done'
        ]);

        $event->update($data);
        return response()->json(['ok' => true, 'event' => $event]);
    }

    public function destroy(CalendarEvent $event) {
      $event->delete();
      return response()->json(['ok'=>true]);
    }
}
