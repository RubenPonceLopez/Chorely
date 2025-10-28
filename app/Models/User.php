<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
}
