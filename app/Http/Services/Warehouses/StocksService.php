<?php

namespace App\Http\Services\Warehouses;

use App\Http\Repositories\Warehouses\StocksRepository;
use App\Http\Resources\Warehouses\StocksDatatableCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StocksService
{
    /**
     * @param Request $request
     * @return JsonResource
     */
    public function datatable(Request $request): JsonResource
    {
        $count = $request->query('page_count', 15);
        $search = $request->query('search', '');
        $datatable = StocksRepository::datatable($count, $search);

        return new StocksDatatableCollection($datatable, $count);
    }
}
