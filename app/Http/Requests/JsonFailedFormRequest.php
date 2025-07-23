<?php

namespace App\Http\Requests;

use App\Exceptions\ValidatorException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class JsonFailedFormRequest extends FormRequest
{
    /**
     * @param Validator $validator
     * @throws ValidatorException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidatorException($validator);
    }
}
