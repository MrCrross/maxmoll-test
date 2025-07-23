<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouses\WarehousesDatatableRequest;
use App\Http\Services\Warehouses\WarehousesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehousesController extends Controller
{
    /**
     * @param WarehousesDatatableRequest $request
     * @param WarehousesService $warehousesService
     * @return JsonResponse
     */
    public function datatable(
        WarehousesDatatableRequest $request,
        WarehousesService $warehousesService
    ): JsonResponse {
        return response()->json($warehousesService->datatable($request));
    }

    /**
     * @param Request $request
     * @param WarehousesService $warehousesService
     * @return JsonResponse
     */
    public function autocomplete(
        Request $request,
        WarehousesService $warehousesService
    ): JsonResponse {
        return response()->json($warehousesService->autocomplete($request));
    }
}
