<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model {
    protected $table = 'calendar_events';

    // Permitir asignación masiva
    protected $fillable = [
        'calendar_id',
        'task_id',
        'assigned_user_id',
        'event_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'color',
        'all_day'
    ];

    // Relaciones
    public function assignedUser() {
        return $this->belongsTo(\App\Models\User::class, 'assigned_user_id');
    }

    public function task() {
        return $this->belongsTo(\App\Models\Task::class, 'task_id');
    }

    public function calendar() {
        return $this->belongsTo(\App\Models\Calendar::class, 'calendar_id');
    }
}
