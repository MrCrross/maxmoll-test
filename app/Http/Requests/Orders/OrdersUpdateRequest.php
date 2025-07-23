<?php

namespace App\Http\Requests\Orders;

use App\Http\Requests\JsonFailedFormRequest;

class OrdersUpdateRequest extends JsonFailedFormRequest
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
            'customer' => 'string|max:255',
            'products' => 'array',
            'products.*.id' => 'required|integer|exists:App\Models\Product,id',
            'products.*.count' => 'required|integer|min:1',
        ];
    }
}
