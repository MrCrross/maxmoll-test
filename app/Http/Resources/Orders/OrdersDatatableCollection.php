<?php

namespace App\Http\Resources\Orders;

use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrdersDatatableCollection extends ResourceCollection
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
            'data' => $this->collection->map(static function (Order $order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->customer,
                    'status' => $order->status,
                    'products' => $order->items->map(static function (OrderItem $orderItem) {
                        return [
                            'id' => $orderItem->product->id,
                            'name' => $orderItem->product->name,
                            'price' => $orderItem->product->price,
                            'count' => $orderItem->count,
                        ];
                    }),
                    'warehouse' => $order->warehouse,
                    'created_at' => $order->created_at,
                    'completed_at' => $order->completed_at,
                ];
            }),
            'per_page' => $this->count,
        ];
    }
}
