<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'field_id',
        'booking_id',
        'rating',
        'review',
        'photos',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'photos' => 'array',
    ];

    protected static function booted(): void
    {
        static::created(fn(Review $r) => Field::recalculateStats($r->field_id));
        static::updated(fn(Review $r) => Field::recalculateStats($r->field_id));
        static::deleted(fn(Review $r) => Field::recalculateStats($r->field_id));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
