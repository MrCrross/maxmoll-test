<?php

namespace App\Http\Requests\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Http\Requests\JsonFailedFormRequest;
use Illuminate\Validation\Rule;

class OrdersUpdateStatusRequest extends JsonFailedFormRequest
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
            'status' => ['required', Rule::enum(OrderStatusEnum::class)],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return array_merge(
            parent::messages(),
            [
                'statuses.*' => 'It can only take the following values: ' . implode(', ', OrderStatusEnum::values()),
            ]
        );
    }
}
