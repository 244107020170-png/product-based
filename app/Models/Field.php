<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}

public function bookings()
{
    return $this->hasMany(Booking::class);
}
}
