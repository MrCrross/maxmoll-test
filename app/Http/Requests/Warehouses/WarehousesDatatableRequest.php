<?php

namespace App\Http\Requests\Warehouses;

use App\Http\Requests\JsonFailedFormRequest;

class WarehousesDatatableRequest extends JsonFailedFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|between:1,100',
            'term' => 'nullable|string',
        ];
    }
}
