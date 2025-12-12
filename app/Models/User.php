<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'apellido',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'is_admin' => 'boolean',
    // si ya tienes email_verified_at o similares, mantenlos
    'email_verified_at' => 'datetime',
    ];

    // Relaciones
    public function flats()
    {
        return $this->belongsToMany(Flat::class, 'flat_members', 'user_id', 'flat_id')
                    ->withPivot('role', 'id');
    }

    public function flatMemberships()
    {
        return $this->hasMany(FlatMember::class, 'user_id');
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class, 'assigned_user_id');
    }
}
