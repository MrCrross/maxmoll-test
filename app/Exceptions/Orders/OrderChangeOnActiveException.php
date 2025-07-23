<?php

namespace App\Exceptions\Orders;

use App\Exceptions\JsonErrorException;

class OrderChangeOnActiveException extends JsonErrorException
{
    public function __construct()
    {
        parent::__construct(
            message: 'It is not possible to change the order status to active. The order has been completed.',
            code: 400
        );
    }
}
