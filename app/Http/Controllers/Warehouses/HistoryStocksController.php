<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouses\HistoryStocksDatatableRequest;
use App\Http\Services\Warehouses\HistoryStocksService;
use Illuminate\Http\JsonResponse;

class HistoryStocksController extends Controller
{
    /**
     * @param HistoryStocksDatatableRequest $request
     * @param HistoryStocksService $historyStocksService
     * @return JsonResponse
     */
    public function datatable(
        HistoryStocksDatatableRequest $request,
        HistoryStocksService $historyStocksService
    ): JsonResponse{
        return response()->json($historyStocksService->datatable($request));
    }
}
