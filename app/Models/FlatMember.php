<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlatMember extends Model
{
    protected $table = 'flat_members';

    protected $fillable = ['flat_id', 'user_id', 'role'];

    public function flat()
    {
        return $this->belongsTo(Flat::class, 'flat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
