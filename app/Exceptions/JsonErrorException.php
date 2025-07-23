<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

abstract class JsonErrorException extends Exception
{
    protected array $additionalResponseData = [];

    public function render(): JsonResponse
    {
        $response = [
            'error' => true,
            'message' => $this->message,
        ];
        if (!empty($this->additionalResponseData)) {
            $response = array_merge($response, $this->additionalResponseData);
        }

        return response()->json($response, $this->code);
    }
}
