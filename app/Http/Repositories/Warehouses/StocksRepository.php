<?php

namespace App\Http\Repositories\Warehouses;

use App\Models\Warehouses\Stock;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StocksRepository
{
    /**
     * @param int $count
     * @param string $search
     * @return Paginator
     */
    public static function datatable(
        int $count,
        string $search
    ): Paginator {
        return Stock::query()
            ->select(
                'id',
                'product_id',
                'warehouse_id',
                'stock',
            )
            ->with(['product', 'warehouse'])
            ->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('warehouse', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->simplePaginate($count);
    }

    public static function getStocksProducts(
        int $warehouseId,
        array $productsIds
    ): Collection {
        return Stock::query()
            ->select(
                'product_id',
                'stock',
            )
            ->where('warehouse_id', '=', $warehouseId)
            ->whereIn('product_id', $productsIds)
            ->pluck('stock', 'product_id');
    }

    public static function reduceStock(
        int $warehouseId,
        int $productId,
        int $reduceStock
    ): void {
        Stock::query()
            ->where('warehouse_id', '=', $warehouseId)
            ->where('product_id', '=', $productId)
            ->update(['stock' => DB::raw('stock - ' . $reduceStock)]);
    }

    public static function returnStock(
        int $warehouseId,
        int $productId,
        int $returnStock
    ): void {
        Stock::query()
            ->where('warehouse_id', '=', $warehouseId)
            ->where('product_id', '=', $productId)
            ->update(['stock' => DB::raw('stock + ' . $returnStock)]);
    }
}
