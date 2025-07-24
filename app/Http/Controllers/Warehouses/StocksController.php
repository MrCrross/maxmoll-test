<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouses\StocksDatatableRequest;
use App\Http\Services\Warehouses\StocksService;
use Illuminate\Http\Resources\Json\JsonResource;

class StocksController extends Controller
{
    /**
     * @param StocksDatatableRequest $request
     * @param StocksService $stocksService
     * @return JsonResource
     */
    public function datatable(
        StocksDatatableRequest $request,
        StocksService $stocksService
    ): JsonResource {
        return $stocksService->datatable($request);
    }
}
