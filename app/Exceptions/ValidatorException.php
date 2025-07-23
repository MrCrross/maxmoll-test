<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class ValidatorException extends Exception
{
    public function __construct(
        protected Validator $validator
    ) {
        parent::__construct();
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => 'Incorrect data',
            'errors' => $this->validator->errors(),
        ], 400);
    }
}
