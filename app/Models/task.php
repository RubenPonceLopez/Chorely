<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['flat_id', 'name', 'description', 'frequency'];

    public function flat()
    {
        return $this->belongsTo(Flat::class, 'flat_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'task_id');
    }
}
