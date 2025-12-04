<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarHistorial;
use App\Models\CalendarEvent;
use App\Models\Calendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalendarHistorialController extends Controller
{
    // -------------------------------------------------------------------------
    //   LISTAR SNAPSHOTS
    // -------------------------------------------------------------------------
    public function listSnapshots(Request $request)
    {
        $flatId = $request->query('flat_id');
        if (!$flatId) {
            return response()->json(['snapshots'=>[]]);
        }

        $snaps = CalendarHistorial::where('flat_id', $flatId)
                    ->orderBy('year','desc')
                    ->orderBy('month','desc')
                    ->get()
                    ->map(function($s){
                        // created_at puede ser Carbon (objeto) o string según cómo se haya guardado.
                        $createdAtFormatted = null;
                        try {
                            if (isset($s->created_at) && $s->created_at instanceof \DateTime) {
                                $createdAtFormatted = $s->created_at->format('Y-m-d H:i:s');
                            } elseif (!empty($s->created_at)) {
                                // intentar parsear con Carbon; si falla, dejar el raw string
                                try {
                                    $createdAtFormatted = Carbon::parse($s->created_at)->format('Y-m-d H:i:s');
                                } catch (\Exception $e) {
                                    // no parseable -> pasar el valor tal cual (string)
                                    $createdAtFormatted = (string)$s->created_at;
                                }
                            } else {
                                $createdAtFormatted = null;
                            }
                        } catch (\Throwable $e) {
                            Log::warning('listSnapshots: error formateando created_at: '.$e->getMessage());
                            $createdAtFormatted = is_scalar($s->created_at) ? (string)$s->created_at : null;
                        }

                        return [
                            'id' => $s->id,
                            'calendar_id' => $s->calendar_id,
                            'flat_id' => $s->flat_id,
                            'year' => $s->year,
                            'month' => $s->month,
                            'versiones' => $s->versiones,
                            'created_at' => $createdAtFormatted
                        ];
                    });

        return response()->json(['snapshots'=>$snaps]);
    }

    // -------------------------------------------------------------------------
    //   SAVE SNAPSHOT (BORRADOR / DEFINITIVO)
    // -------------------------------------------------------------------------
    public function saveSnapshot(Request $request)
    {
        $data = $request->validate([
            'calendar_id' => 'required|integer|exists:calendars,id',
            'flat_id' => 'required|integer|exists:flats,id',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'versiones' => 'required|string',
            'events' => 'required|array'
        ]);

        $calendarId = $data['calendar_id'];
        $flatId     = $data['flat_id'];
        $year       = $data['year'];
        $month      = $data['month'];
        $versiones  = $data['versiones'];
        $events     = $data['events'];

        DB::beginTransaction();
        try {
            CalendarEvent::where('calendar_id',$calendarId)
                ->whereYear('event_date',$year)
                ->whereMonth('event_date',$month)
                ->delete();

            if ($versiones === 'Definitivo') {
                foreach ($events as $item) {
                    if (empty($item['event_date'])) continue;
                    CalendarEvent::create([
                        'calendar_id' => $calendarId,
                        'task_id' => $item['task_id'] ?? null,
                        'assigned_user_id' => $item['assigned_user_id'] ?? null,
                        'event_date' => $item['event_date'],
                        'start_time' => $item['start_time'] ?? null,
                        'end_time' => $item['end_time'] ?? null,
                        'status' => $item['status'] ?? null,
                        'notes' => $item['notes'] ?? null,
                        'color' => $item['color'] ?? null,
                        'all_day' => isset($item['all_day']) ? (bool)$item['all_day'] : true
                    ]);
                }
            }

            CalendarHistorial::create([
                'calendar_id' => $calendarId,
                'flat_id' => $flatId,
                'year' => $year,
                'month' => $month,
                'versiones' => $versiones,
                'snapshot' => $events,
                // Guardamos created_at como ahora mismo en formato ISO para consistencia
                'created_at' => now()
            ]);

            DB::commit();
            return response()->json(['ok'=>true, 'message'=>'Snapshot guardado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saveSnapshot: '.$e->getMessage());
            return response()->json(['ok'=>false,'message'=>'Error al guardar snapshot.'],500);
        }
    }

    // -------------------------------------------------------------------------
    //      CLONAR DESDE HISTORIAL → aplicar snapshot al mismo calendar_id
    // -------------------------------------------------------------------------
    public function cloneFromHistorial(Request $request)
    {
        $data = $request->validate([
            'historial_id' => 'required|integer|exists:calendar_historial,id',
            'target_calendar_id' => 'required|integer|exists:calendars,id',
            'target_year' => 'required|integer',
            'target_month' => 'required|integer|min:1|max:12',
        ]);

        $hist = CalendarHistorial::findOrFail($data['historial_id']);
        $snapshot = $hist->snapshot;
        if (!is_array($snapshot) || empty($snapshot)) {
            return response()->json(['ok'=>false,'message'=>'Snapshot vacío.'],400);
        }

        $targetCalendar = Calendar::findOrFail($data['target_calendar_id']);
        $targetYear  = (int)$data['target_year'];
        $targetMonth = (int)$data['target_month'];

        $filtered = [];
        foreach ($snapshot as $item) {
            if (empty($item['event_date'])) continue;
            try {
                $d = Carbon::parse($item['event_date']);
            } catch(\Exception $e){
                continue;
            }
            if ((int)$d->year === (int)$hist->year &&
                (int)$d->month === (int)$hist->month) {
                $filtered[] = $item;
            }
        }
        if (empty($filtered)) {
            return response()->json(['ok'=>false,'message'=>'El snapshot no contiene eventos de su mes original.'],400);
        }

        DB::beginTransaction();
        try {
            CalendarEvent::where('calendar_id',$targetCalendar->id)
                ->whereYear('event_date',$targetYear)
                ->whereMonth('event_date',$targetMonth)
                ->delete();

            $targetMonthStart = Carbon::create($targetYear,$targetMonth,1)->startOfMonth();
            $lastDay = $targetMonthStart->copy()->endOfMonth()->day;

            foreach ($filtered as $item) {
                try { $srcDay = Carbon::parse($item['event_date'])->day; }
                catch(\Exception $e){ $srcDay = 1; }

                $destDay = min($srcDay, $lastDay);
                $destDate = Carbon::create($targetYear,$targetMonth,$destDay)->format('Y-m-d');

                CalendarEvent::create([
                    'calendar_id' => $targetCalendar->id,
                    'task_id' => $item['task_id'] ?? null,
                    'assigned_user_id' => $item['assigned_user_id'] ?? null,
                    'event_date' => $destDate,
                    'start_time' => $item['start_time'] ?? null,
                    'end_time' => $item['end_time'] ?? null,
                    'status' => $item['status'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'color' => $item['color'] ?? null,
                    'all_day' => isset($item['all_day']) ? (bool)$item['all_day'] : true
                ]);
            }

            CalendarHistorial::create([
                'calendar_id' => $targetCalendar->id,
                'flat_id' => $targetCalendar->flat_id,
                'year' => $targetYear,
                'month' => $targetMonth,
                'versiones' => 'Borrador',
                'snapshot' => $filtered,
                'created_at' => now()
            ]);

            DB::commit();
            return response()->json(['ok'=>true,'message'=>'Duplicación aplicada correctamente.']);
        } catch(\Exception $e){
            DB::rollBack();
            Log::error('cloneFromHistorial error: '.$e->getMessage());
            return response()->json(['ok'=>false,'message'=>'Error interno al duplicar.'],500);
        }
    }

    // Aux endpoints antiguos no usados pero mantenidos por compatibilidad:
    public function existsForMonth() { return response()->json(['ok'=>true]); }
    public function cloneFromPrevious() { return response()->json(['ok'=>false,'message'=>'No usado']); }
}
