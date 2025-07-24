<?php

namespace App\Http\Services\Products;

use App\Http\Repositories\Products\ProductsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductsService
{
    public function __construct(
        private ProductsRepository $productsRepository = new ProductsRepository(),
    )
    {
    }

    public function autocomplete(Request $request): Collection
    {
        return $this->productsRepository->autocomplete($request->query('term', ''));
    }
}
