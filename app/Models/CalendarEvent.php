<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model {
    protected $table = 'calendar_events';

    public function users() {
        return $this->belongsToMany(User::class, 'calendar_event_user', 'calendar_event_id', 'user_id');
    }

    public function task() {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function calendar() {
        return $this->belongsTo(Calendar::class, 'calendar_id');
    }
}
