<?php

namespace App\Http\Repositories\Warehouses;

use App\Models\Warehouses\HistoryStock;
use Illuminate\Contracts\Pagination\Paginator;

class HistoryStocksRepository
{
    /**
     * @param int $count
     * @param string $dateStart
     * @param string $dateEnd
     * @param array $warehousesIds
     * @param array $productsIds
     * @return Paginator
     */
    public function datatable(
        int $count,
        string $dateStart = '',
        string $dateEnd = '',
        array $warehousesIds = [],
        array $productsIds = []
    ): Paginator {
        return HistoryStock::query()
            ->select(
                'id',
                'order_id',
                'product_id',
                'warehouse_id',
                'created_at',
                'quantity',
            )
            ->with(['order', 'product', 'warehouse'])
            ->when($dateStart !== '', function ($query) use ($dateStart) {
                $query->where('created_at', '>=', $dateStart);
            })
            ->when($dateEnd !== '', function ($query) use ($dateEnd) {
                $query->where('created_at', '<=', $dateEnd);
            })
            ->when(!empty($productsIds), function ($query) use ($productsIds) {
                $query->whereHas('product', function ($query) use ($productsIds) {
                    $query->whereIn('id', $productsIds);
                });
            })
            ->when(!empty($warehousesIds), function ($query) use ($warehousesIds) {
                $query->whereHas('warehouse', function ($query) use ($warehousesIds) {
                    $query->whereIn('id', $warehousesIds);
                });
            })
            ->orderBy('created_at', 'desc')
            ->simplePaginate($count);
    }

    /**
     * @param array $fields
     * @return void
     */
    public function create(array $fields): void
    {
        HistoryStock::query()
            ->insert($fields);
    }
}
