<?php

namespace App\Exceptions\Orders;

use App\Exceptions\JsonErrorException;

class OrderChangeOnCompletedException extends JsonErrorException
{
    public function __construct()
    {
        parent::__construct(
            message: 'The application has been canceled. The application must be activated.',
            code: 400
        );
    }
}
