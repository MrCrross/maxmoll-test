<?php

namespace App\Http\Repositories\Orders;

use App\Models\Orders\Order;
use Illuminate\Contracts\Pagination\Paginator;

class OrdersRepository
{
    /**
     * @param int $count - количество элементов страницы
     * @param string $customer - поиск по ФИО клиента
     * @param array $statuses - массив статусов заявок
     * @param array $warehousesIds - массив ид складов
     * @param array $productsIds - массив ид товаров
     * @return Paginator
     */
    public static function datatable(
        int $count,
        string $customer = '',
        array $statuses = [],
        array $warehousesIds = [],
        array $productsIds = []
    ): Paginator {
        return Order::query()
            ->select(
                'id',
                'customer',
                'warehouse_id',
                'created_at',
                'completed_at',
                'status'
            )
            ->with(['warehouse', 'items.product'])
            ->when($customer !== '', function ($query) use ($customer) {
                $query->where('customer', 'like', '%' . $customer . '%');
            })
            ->when(!empty($statuses), function ($query) use ($statuses) {
                $query->whereIn('status', $statuses);
            })
            ->when(!empty($warehousesIds), function ($query) use ($warehousesIds) {
                $query->whereIn('warehouse_id', $warehousesIds);
            })
            ->when(!empty($productsIds), function ($query) use ($productsIds) {
                $query->whereHas('items', function ($itemQuery) use ($productsIds) {
                    $itemQuery->whereIn('product_id', $productsIds);
                });
            })
            ->orderBy('id', 'desc')
            ->simplePaginate($count);
    }

    /**
     * @param int $id
     * @return Order
     */
    public static function getWithStocksById(int $id): Order
    {
        return Order::query()
            ->select(
                'id',
                'customer',
                'warehouse_id',
                'created_at',
                'completed_at',
                'status'
            )
            ->with(['warehouse', 'items.product'])
            ->where('id', '=', $id)
            ->firstOrFail();
    }

    /**
     * @param int $id
     * @return Order|null
     */
    public static function getById(int $id): Order|null
    {
        return Order::query()
            ->select(
                'id',
                'customer',
                'warehouse_id',
                'created_at',
                'completed_at',
                'status'
            )
            ->find($id);
    }

    /**
     * @param array $fields
     * @return Order
     */
    public static function create(array $fields): Order
    {
        return Order::query()
            ->create($fields);
    }

    /**
     * @param int $orderId
     * @param array $fields
     * @return void
     */
    public static function update(int $orderId, array $fields): void
    {
        Order::query()
            ->where('id', '=', $orderId)
            ->update($fields);
    }
}
