<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarHistorial extends Model
{
    protected $table = 'calendar_historial';

    // Si la tabla sólo tiene created_at y no updated_at:
    public $timestamps = false;

    protected $fillable = [
        'calendar_id',
        'flat_id',
        'year',
        'month',
        'snapshot', // JSON con la distribución
        'created_at'
    ];

    // Mutator para snapshot JSON
    protected $casts = [
        'snapshot' => 'array'
    ];
}
