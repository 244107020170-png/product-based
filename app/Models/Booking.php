<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'field_id',
        'court_number',
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
        'court_number' => 'integer',
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

    /**
     * Get the display status key for this booking.
     * Checks if confirmed/active bookings have passed their end time.
     */
    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'waiting_payment' && $this->payment_deadline && now()->greaterThan($this->payment_deadline)) {
            return 'expired';
        }

        $nonSelesaiStatuses = ['cancelled', 'expired', 'rejected'];

        if (in_array($this->status, $nonSelesaiStatuses, true)) {
            return $this->status;
        }

        $bookingEnd = \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->end_time);
        if ($bookingEnd->isPast()) {
            return 'selesai';
        }

        return $this->status;
    }

    /**
     * Get standardized display info for a status key.
     *
     * @return array{key: string, label: string, class: string, bg: string, color: string, dot: string}
     */
    public static function statusDisplayInfo(?string $statusKey): array
    {
        return match ($statusKey) {
            'selesai', 'completed' => [
                'key'   => 'selesai',
                'label' => 'Selesai',
                'class' => 'history-status--selesai',
                'bg'    => '#dbeafe',
                'color' => '#1d4ed8',
                'dot'   => '#3b82f6',
            ],
            'confirmed' => [
                'key'   => 'confirmed',
                'label' => 'Terkonfirmasi',
                'class' => 'history-status--akan',
                'bg'    => '#bbf7d0',
                'color' => '#166534',
                'dot'   => '#16a34a',
            ],
            'paid' => [
                'key'   => 'paid',
                'label' => 'Dibayar',
                'class' => 'history-status--akan',
                'bg'    => '#d1fae5',
                'color' => '#065f46',
                'dot'   => '#10b981',
            ],
            'pending' => [
                'key'   => 'pending',
                'label' => 'Menunggu',
                'class' => 'history-status--pending',
                'bg'    => '#fef3c7',
                'color' => '#92400e',
                'dot'   => '#d97706',
            ],
            'waiting_payment' => [
                'key'   => 'waiting_payment',
                'label' => 'Menunggu Pembayaran',
                'class' => 'history-status--pending',
                'bg'    => '#fef3c7',
                'color' => '#92400e',
                'dot'   => '#d97706',
            ],
            'waiting_confirmation' => [
                'key'   => 'waiting_confirmation',
                'label' => 'Menunggu Konfirmasi',
                'class' => 'history-status--pending',
                'bg'    => '#fef3c7',
                'color' => '#92400e',
                'dot'   => '#d97706',
            ],
            'cancelled' => [
                'key'   => 'cancelled',
                'label' => 'Dibatalkan',
                'class' => 'history-status--dibatal',
                'bg'    => '#fee2e2',
                'color' => '#991b1b',
                'dot'   => '#ef4444',
            ],
            'expired' => [
                'key'   => 'expired',
                'label' => 'Kadaluarsa',
                'class' => 'history-status--dibatal',
                'bg'    => '#fee2e2',
                'color' => '#842029',
                'dot'   => '#dc2626',
            ],
            'rejected' => [
                'key'   => 'rejected',
                'label' => 'Ditolak',
                'class' => 'history-status--dibatal',
                'bg'    => '#fee2e2',
                'color' => '#991b1b',
                'dot'   => '#dc2626',
            ],
            default => [
                'key'   => $statusKey ?? 'unknown',
                'label' => $statusKey ? ucfirst(str_replace('_', ' ', $statusKey)) : 'Menunggu',
                'class' => 'history-status--pending',
                'bg'    => '#f4f6fb',
                'color' => '#111',
                'dot'   => '#43a680',
            ],
        };
    }
}
