<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Calendar extends Model {
  protected $fillable = ['flat_id','name','month_start','created_by'];
  public function events() { return $this->hasMany(CalendarEvent::class); }
  public function flat() { return $this->belongsTo(Flat::class); }
}