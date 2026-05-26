<?php

namespace App\Enums;

final class PaymentStatus
{
    public const WAITING = 'waiting';
    public const PAID = 'paid';

    public static function all(): array
    {
        return [self::WAITING, self::PAID];
    }
}
