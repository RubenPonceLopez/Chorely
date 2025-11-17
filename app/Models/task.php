<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // La tabla tasks solo define created_at en el dump SQL
    public $timestamps = false;
    protected $fillable = ['flat_id', 'name', 'description'];

    public function flat()
    {
        return $this->belongsTo(Flat::class, 'flat_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'task_id');
    }

    // Eventos del calendario asociados a esta tarea
    public function events()
    {
        return $this->hasMany(CalendarEvent::class, 'task_id');
    }

}
