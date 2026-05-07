<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'field_id',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'date'       => 'date',
        'start_time' => 'string',
        'end_time'   => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Calculate total price based on field price_per_hour and booking duration.
     */
    public function getTotalPriceAttribute(): int
    {
        if (!$this->field) return 0;

        $start = \Carbon\Carbon::parse($this->start_time);
        $end   = \Carbon\Carbon::parse($this->end_time);
        $hours = max(1, $start->diffInHours($end));

        return $this->field->price_per_hour * $hours;
    }

    /**
     * Scopes
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeAkanDatang($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeDibatalkan($query)
    {
        return $query->where('status', 'cancelled');
    }
}
