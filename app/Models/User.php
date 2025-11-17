<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'apellido', // agregado
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relaciones
    public function flats()
    {
        return $this->belongsToMany(Flat::class, 'flat_members', 'user_id', 'flat_id')
                    ->withPivot('role', 'id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'assigned_user_id');
    }

    public function vacations()
    {
        return $this->hasMany(Vacation::class, 'user_id');
    }

    public function flatMemberships(){
    return $this->hasMany(FlatMember::class, 'user_id');
}

 public function calendarEvents() {
        return $this->belongsToMany(CalendarEvent::class, 'calendar_event_user', 'user_id', 'calendar_event_id');
    }

}
