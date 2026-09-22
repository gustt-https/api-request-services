<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case RECEIVED = 'received';
    case CANCELED = 'canceled';
    case PENDING = 'pending';

    public static function fromProvider(string $status): self
    {
        return match (strtoupper($status)) {
            'PENDING' => self::PENDING,
            'RECEIVED', 'CONFIRMED' => self::RECEIVED,
            'DELETED', 'REFUNDED' => self::CANCELED,
            default => self::from(strtolower($status)),
        };
    }
}
