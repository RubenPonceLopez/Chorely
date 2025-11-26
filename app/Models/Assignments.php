<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'flat_id',
        'task_id',
        'year',
        'week_number',
        'day_of_week',
        'assigned_user_id'
    ];

    public function flat()
    {
        return $this->belongsTo(Flat::class, 'flat_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
