<?php

namespace App\Http\Services\Products;

use App\Http\Repositories\Products\ProductsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductsService
{
    public function autocomplete(Request $request): Collection
    {
        return ProductsRepository::autocomplete($request->query('term', ''));
    }
}
