<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'field_id',
        'date',
        'is_holiday',
    ];

    protected $casts = [
        'date' => 'date',
        'is_holiday' => 'boolean',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
