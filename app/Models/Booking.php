<?php

namespace App\Models;

use App\Enums\BookingStatus;
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
        'payment_deadline',
        'expired_at',
        'paid_at',
        'confirmed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'payment_deadline' => 'datetime',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function isWaitingPayment(): bool
    {
        return $this->status === BookingStatus::WAITING_PAYMENT;
    }

    public function isWaitingConfirmation(): bool
    {
        return $this->status === BookingStatus::WAITING_CONFIRMATION;
    }

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatus::CONFIRMED;
    }

    public function getPaymentDeadlineCountdownAttribute(): string
    {
        if (! $this->payment_deadline) {
            return '-';
        }

        return now()->diffForHumans($this->payment_deadline, [
            'parts' => 2,
            'short' => true,
            'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function getOriginalTotalPriceAttribute(): int
    {
        if (!$this->field) return 0;
        $start = \Carbon\Carbon::parse($this->start_time);
        $end   = \Carbon\Carbon::parse($this->end_time);
        $hours = max(1, $start->diffInHours($end));
        return $this->field->price_per_hour * $hours;
    }

    public function getDiscountAmountAttribute(): int
    {
        return $this->original_total_price - $this->subtotal_price;
    }

    public function getSubtotalPriceAttribute(): int
    {
        if (!$this->field) return 0;

        $start = \Carbon\Carbon::parse($this->start_time);
        $end   = \Carbon\Carbon::parse($this->end_time);
        $hours = max(1, $start->diffInHours($end));

        $pricePerHour = $this->field->price_per_hour;

        $promo = $this->field->discounts()->active()->first();
        if (!$promo) {
            $promo = \App\Models\Discount::where('owner_id', $this->field->owner_id)
                ->whereNull('field_id')
                ->active()
                ->first();
        }

        if ($promo) {
            if ($promo->type === 'percentage') {
                $pricePerHour = (int) round($pricePerHour * (1 - $promo->value / 100));
            } else {
                $pricePerHour = max(0, $pricePerHour - (int) $promo->value);
            }
        }

        return $pricePerHour * $hours;
    }

    public function getTotalPriceAttribute(): int
    {
        return $this->subtotal_price + 2000;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', BookingStatus::activeStatuses());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', BookingStatus::COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', BookingStatus::CANCELLED);
    }

    public function scopePending($query)
    {
        return $query->where('status', BookingStatus::PENDING);
    }

    public function isActive(): bool
    {
        return in_array($this->status, BookingStatus::activeStatuses(), true);
    }
}
