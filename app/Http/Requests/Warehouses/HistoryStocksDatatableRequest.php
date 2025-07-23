<?php

namespace App\Http\Requests\Warehouses;

use App\Http\Requests\JsonFailedFormRequest;

class HistoryStocksDatatableRequest extends JsonFailedFormRequest
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
            'page_count' => 'nullable|integer|between:1,100',
            'warehouses' => 'nullable|array',
            'warehouses.*' => 'required|integer|exists:App\Models\Warehouses\Warehouse,id',
            'products' => 'nullable|array',
            'products.*' => 'required|integer|exists:App\Models\Product,id',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date',
        ];
    }
}
