<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\CalendarHistorial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // import correcto de la fachada Log

class CalendarHistorialController extends Controller
{
    // Comprueba si existe calendario para flat y mes objetivo
    public function existsForMonth(Request $request)
    {
        $request->validate([
            'flat_id' => 'required|integer|exists:flats,id',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12'
        ]);

        $monthStart = Carbon::create($request->year, $request->month, 1)->format('Y-m-d');

        $exists = Calendar::where('flat_id', $request->flat_id)
            ->where('month_start', $monthStart)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    // Clone + create new calendar for target month using source calendar's distribution
    public function cloneFromPrevious(Request $request)
    {
        $data = $request->validate([
            'source_calendar_id' => 'required|integer|exists:calendars,id',
            'target_year' => 'required|integer',
            'target_month' => 'required|integer|min:1|max:12',
            'name' => 'nullable|string|max:150'
        ]);

        $source = Calendar::findOrFail($data['source_calendar_id']);
        $targetMonthStart = Carbon::create($data['target_year'], $data['target_month'], 1)->startOfMonth();

        // comprobar si ya existe target
        if (Calendar::where('flat_id', $source->flat_id)
            ->where('month_start', $targetMonthStart->format('Y-m-d'))
            ->exists()) {
            return response()->json(['ok'=>false, 'message' => 'El calendario destino ya existe.'], 409);
        }

        DB::beginTransaction();
        try {
            $new = Calendar::create([
                'flat_id' => $source->flat_id,
                'name' => $data['name'] ?? ($source->name . ' - ' . $targetMonthStart->format('F Y')),
                'month_start' => $targetMonthStart->format('Y-m-d'),
                'created_by' => Auth::id() ?? $source->created_by,
            ]);

            $sourceEvents = CalendarEvent::where('calendar_id', $source->id)->get();

            $snapshots = [];

            $targetMonthLastDay = $targetMonthStart->copy()->endOfMonth()->day;

            foreach ($sourceEvents as $ev) {
                $day = Carbon::parse($ev->event_date)->day;
                $targetDay = min($day, $targetMonthLastDay);
                $newDate = Carbon::create($data['target_year'], $data['target_month'], $targetDay)->format('Y-m-d');

                CalendarEvent::create([
                    'calendar_id' => $new->id,
                    'task_id' => $ev->task_id,
                    'assigned_user_id' => $ev->assigned_user_id,
                    'event_date' => $newDate,
                    'start_time' => $ev->start_time,
                    'end_time' => $ev->end_time,
                    'status' => $ev->status,
                    'notes' => $ev->notes,
                    'color' => $ev->color,
                    'all_day' => $ev->all_day
                ]);

                $snapshots[] = [
                    'task_id' => $ev->task_id,
                    'assigned_user_id' => $ev->assigned_user_id,
                    'source_date' => $ev->event_date,
                    'target_date' => $newDate,
                    'status' => $ev->status
                ];
            }

            // Guardar historial (snapshot)
            CalendarHistorial::create([
                'calendar_id' => $new->id,
                'flat_id' => $new->flat_id,
                'year' => $data['target_year'],
                'month' => $data['target_month'],
                'snapshot' => $snapshots,
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json(['ok'=>true, 'calendar_id' => $new->id, 'redirect' => route('calendars.show', $new->id)]);
        } catch (\Exception $e) {
            DB::rollBack();
            // usa la fachada importada arriba (Log::error)
            Log::error('Error clonando mes: '.$e->getMessage()."\n".$e->getTraceAsString());
            return response()->json(['ok'=>false, 'message' => 'Error interno.'], 500);
        }
    }

    /**
     * Guarda un snapshot del calendario actual en calendar_historial
     * Request: { calendar_id: int }
     */
    public function saveSnapshot(Request $request)
    {
        $data = $request->validate([
            'calendar_id' => 'required|integer|exists:calendars,id'
        ]);

        $calendar = Calendar::with('flat.members')->findOrFail($data['calendar_id']);
        $userId = Auth::id();

        // Autorización básica: creado por o miembro del piso
        $isMember = ($calendar->created_by == $userId)
            || ($calendar->flat && $calendar->flat->members->pluck('user_id')->contains($userId));

        if (! $isMember) {
            return response()->json(['ok' => false, 'message' => 'No autorizado.'], 403);
        }

        try {
            // Obtener eventos actuales del calendario (los que están guardados en DB)
            $events = CalendarEvent::where('calendar_id', $calendar->id)->get();

            $snapshot = $events->map(function($ev) {
                return [
                    'task_id' => $ev->task_id,
                    'assigned_user_id' => $ev->assigned_user_id,
                    'event_date' => $ev->event_date,
                    'start_time' => $ev->start_time,
                    'end_time' => $ev->end_time,
                    'status' => $ev->status,
                    'notes' => $ev->notes,
                    'color' => $ev->color,
                    'all_day' => (bool) $ev->all_day
                ];
            })->toArray();

            // Guardar snapshot en calendar_historial
            CalendarHistorial::create([
                'calendar_id' => $calendar->id,
                'flat_id' => $calendar->flat_id,
                'year' => Carbon::parse($calendar->month_start)->year,
                'month' => Carbon::parse($calendar->month_start)->month,
                'snapshot' => $snapshot,
                'created_at' => now()
            ]);

            return response()->json(['ok' => true, 'message' => 'Distribución guardada correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error guardando snapshot calendario: '.$e->getMessage()."\n".$e->getTraceAsString());
            return response()->json(['ok' => false, 'message' => 'Error interno al guardar snapshot.'], 500);
        }
    }
}
