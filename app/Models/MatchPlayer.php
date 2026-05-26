<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enums\PaymentStatus;

class MatchPlayer extends Model
{
    protected $fillable = [
        'match_id',
        'user_id',
        'contribution_amount',
        'payment_status',
        'paid_at',
        'confirmed_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function match()
    {
        return $this->belongsTo(Matchs::class, 'match_id');
    }

    public function isWaiting(): bool
    {
        return $this->payment_status === PaymentStatus::WAITING;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === PaymentStatus::PAID;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
