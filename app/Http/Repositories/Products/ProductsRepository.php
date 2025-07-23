<?php

namespace App\Http\Repositories\Products;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductsRepository
{
    /**
     * @param string $term
     * @return Collection
     */
    public static function autocomplete(string $term): Collection
    {
        return Product::query()
            ->select('id as value', 'name as label')
            ->where('name', 'like', '%' . $term . '%')
            ->limit(10)
            ->get();
    }

    /**
     * @param int $id
     * @return Product|null
     */
    public static function getById(int $id): Product|null
    {
        return Product::query()
            ->find(
                $id,
                [
                    'id',
                    'name',
                ]
            );
    }
}
