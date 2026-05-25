<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'field_id',
        'task_name',
        'type',
        'schedule_date',
        'priority',
        'pic_name',
        'status',
        'notes',
    ];

    protected $casts = [
        'schedule_date' => 'date',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
