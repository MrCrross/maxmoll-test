<?php

namespace App\Http\Repositories\Orders;

use App\Models\Orders\OrderItem;
use Illuminate\Support\Collection;

class OrderItemsRepository
{
    /**
     * @param int $orderId
     * @return Collection
     */
    public function getByOrderId(int $orderId): Collection
    {
        return OrderItem::query()
            ->select(
                'order_items.id',
                'order_items.order_id',
                'orders.warehouse_id',
                'order_items.product_id',
                'order_items.count',
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.order_id', '=', $orderId)
            ->get();
    }

    /**
     * @param array $fields
     * @return void
     */
    public function insert(array $fields): void
    {
        OrderItem::query()->insert($fields);
    }

    /**
     * @param int $orderId
     * @param int $productId
     * @param array $fields
     * @return void
     */
    public function updateByProductId(int $orderId, int $productId, array $fields): void
    {
        OrderItem::query()
            ->where('order_id', '=', $orderId)
            ->where('product_id', '=', $productId)
            ->update($fields);
    }

    /**
     * @param int $orderId
     * @param array $productsIds
     * @return void
     */
    public function deleteByProductsIds(int $orderId, array $productsIds): void
    {
        OrderItem::query()
            ->where('order_id', '=', $orderId)
            ->whereIn('product_id', $productsIds)
            ->delete();
    }
}
