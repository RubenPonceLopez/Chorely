<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $fillable = ['flat_id', 'user_id', 'year', 'week_number', 'note'];

    public function flat()
    {
        return $this->belongsTo(Flat::class, 'flat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
