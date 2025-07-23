<?php

namespace App\Http\Resources\Warehouses;

use App\Models\Warehouses\HistoryStock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HistoryStockDatatableCollection extends ResourceCollection
{
    public function __construct(
        mixed $resource,
        private readonly int $count,
    ){
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(static function (HistoryStock $history) {
                return [
                    'id' => $history->id,
                    'order_id' => $history->order->id,
                    'order_customer' => $history->order->customer,
                    'product' => [
                        'id' => $history->product->id,
                        'name' => $history->product->name,
                    ],
                    'warehouse' => [
                        'id' => $history->warehouse->id,
                        'name' => $history->warehouse->name,
                    ],
                    'quantity' => $history->quantity,
                    'created_at' => $history->created_at
                ];
            }),
            'per_page' => $this->count,
        ];
    }
}
