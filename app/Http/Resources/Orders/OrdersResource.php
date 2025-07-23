<?php

namespace App\Http\Resources\Orders;

use App\Models\Orders\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer,
            'status' => $this->status,
            'products' => $this->items->map(static function (OrderItem $orderItem) {
                return [
                    'id' => $orderItem->product->id,
                    'name' => $orderItem->product->name,
                    'price' => $orderItem->product->price,
                    'count' => $orderItem->count,
                ];
            }),
            'warehouse' => [
                'id' => $this->warehouse->id,
                'name' => $this->warehouse->name,
            ],
            'created_at' => $this->created_at,
            'completed_at' => $this->completed_at,
        ];
    }
}
