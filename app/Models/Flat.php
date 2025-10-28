<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    protected $fillable = ['name', 'description'];

    public function members()
    {
        return $this->hasMany(FlatMember::class, 'flat_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'flat_members', 'flat_id', 'user_id')
                    ->withPivot('role', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'flat_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'flat_id');
    }

    public function vacations()
    {
        return $this->hasMany(Vacation::class, 'flat_id');
    }
}
