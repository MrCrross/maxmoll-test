<?php

namespace App\Enums\Orders;

enum OrderStatusEnum: string
{
    case Active = 'active';
    case Completed = 'completed';
    case Canceled = 'canceled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
