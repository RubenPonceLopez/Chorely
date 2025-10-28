<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CalendarEvent extends Model {
  protected $fillable = ['calendar_id','task_id','assigned_user_id','event_date','start_time','end_time','status','notes'];
  public function calendar() { return $this->belongsTo(Calendar::class); }
  public function task() { return $this->belongsTo(Task::class); }
  public function user() { return $this->belongsTo(User::class,'assigned_user_id'); }
}