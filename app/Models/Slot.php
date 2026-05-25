<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $fillable = [
        'field_id',
        'date',
        'hour',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
