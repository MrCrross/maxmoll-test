<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouses\HistoryStocksDatatableRequest;
use App\Http\Services\Warehouses\HistoryStocksService;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryStocksController extends Controller
{
    /**
     * @param HistoryStocksDatatableRequest $request
     * @param HistoryStocksService $historyStocksService
     * @return JsonResource
     */
    public function datatable(
        HistoryStocksDatatableRequest $request,
        HistoryStocksService $historyStocksService
    ): JsonResource {
        return $historyStocksService->datatable($request);
    }
}
