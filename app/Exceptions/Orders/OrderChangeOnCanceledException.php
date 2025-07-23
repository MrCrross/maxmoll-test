<?php

namespace App\Exceptions\Orders;

use App\Exceptions\JsonErrorException;

class OrderChangeOnCanceledException extends JsonErrorException
{
    public function __construct()
    {
        parent::__construct(
            message: 'It is not possible to cancel the order. The order has already been completed.',
            code: 400
        );
    }
}
