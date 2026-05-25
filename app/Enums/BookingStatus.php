<?php

namespace App\Enums;

final class BookingStatus
{
    public const PENDING = 'pending';
    public const WAITING_PAYMENT = 'waiting_payment';
    public const PAID = 'paid';
    public const CONFIRMED = 'confirmed';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';
    public const EXPIRED = 'expired';
    public const REJECTED = 'rejected';

    public static function activeStatuses(): array
    {
        return [
            self::PENDING,
            self::WAITING_PAYMENT,
            self::PAID,
            self::CONFIRMED,
        ];
    }

    public static function terminalStatuses(): array
    {
        return [
            self::COMPLETED,
            self::CANCELLED,
            self::EXPIRED,
            self::REJECTED,
        ];
    }

    public static function all(): array
    {
        return array_merge(self::activeStatuses(), self::terminalStatuses());
    }
}
