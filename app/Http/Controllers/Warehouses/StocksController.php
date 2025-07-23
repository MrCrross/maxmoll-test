<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouses\StocksDatatableRequest;
use App\Http\Services\Warehouses\StocksService;
use Illuminate\Http\JsonResponse;

class StocksController extends Controller
{
    /**
     * @param StocksDatatableRequest $request
     * @param StocksService $stocksService
     * @return JsonResponse
     */
    public function datatable(
        StocksDatatableRequest $request,
        StocksService $stocksService
    ): JsonResponse {
        return response()->json($stocksService->datatable($request));
    }
}
