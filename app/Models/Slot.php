<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $fillable = [
        'field_id',
        'court_number',
        'date',
        'hour',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'court_number' => 'integer',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
