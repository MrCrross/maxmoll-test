<?php

namespace App\Http\Repositories\Warehouses;

use App\Models\Warehouses\Warehouse;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

class WarehousesRepository
{
    /**
     * @param int $count - количество элементов на странице
     * @param string $term - строка для поиска
     * @return Paginator
     */
    public static function datatable(int $count, string $term): Paginator
    {
        return Warehouse::query()
                ->select(
                    'id',
                    'name'
                )
                ->where('name', 'like', '%' . $term . '%')
                ->simplePaginate($count);
    }

    /**
     * @param string $term - строка для поиска
     * @return Collection
     */
    public static function autocomplete(string $term): Collection
    {
        return Warehouse::query()
            ->select('id as value', 'name as label')
            ->where('name', 'like', '%' . $term . '%')
            ->limit(10)
            ->get();
    }

    public static function getById(int $id): Warehouse
    {
        return Warehouse::query()
            ->find(
                $id,
                [
                    'id',
                    'name',
                ]
            );
    }
}
