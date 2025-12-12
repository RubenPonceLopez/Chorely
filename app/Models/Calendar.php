<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model {
  // La tabla calendars en el SQL solo define created_at, no updated_at
  public $timestamps = false;

  // Añadimos year, month y name para permitir edición desde forms
  protected $fillable = [
      'flat_id',
      'name',
      'month_start',
      'created_by',
      'year',
      'month',
  ];

  public function events()
  {
    return $this->hasMany(CalendarEvent::class, 'calendar_id');
  }

  public function flat()
  {
    return $this->belongsTo(Flat::class);
  }
}
