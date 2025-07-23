<?php

namespace App\Exceptions\Auth;

use App\Exceptions\JsonErrorException;

class UnauthorizedException extends JsonErrorException
{
    public function __construct()
    {
        parent::__construct(
            'Unauthorized',
            401
        );
    }
}
