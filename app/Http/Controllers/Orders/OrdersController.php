<?php

namespace App\Http\Controllers\Orders;

use App\Exceptions\Orders\OrderLargeStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrdersCreateRequest;
use App\Http\Requests\Orders\OrdersDatatableRequest;
use App\Http\Requests\Orders\OrdersUpdateRequest;
use App\Http\Requests\Orders\OrdersUpdateStatusRequest;
use App\Http\Services\Orders\OrdersService;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    /**
     * @param OrdersDatatableRequest $request
     * @param OrdersService $ordersService
     * @return JsonResponse
     */
    public function datatable(
        OrdersDatatableRequest $request,
        OrdersService $ordersService
    ): JsonResponse {
        return response()->json($ordersService->datatable($request));
    }

    /**
     * @param OrdersCreateRequest $request
     * @param OrdersService $ordersService
     * @return JsonResponse
     * @throws OrderLargeStockException
     */
    public function create(
        OrdersCreateRequest $request,
        OrdersService $ordersService
    ): JsonResponse {
        return response()->json($ordersService->create($request));
    }

    /**
     * @param int $orderId
     * @param OrdersUpdateRequest $request
     * @param OrdersService $ordersService
     * @return JsonResponse
     * @throws OrderLargeStockException
     */
    public function update(
        int $orderId,
        OrdersUpdateRequest $request,
        OrdersService $ordersService
    ): JsonResponse {
        return response()->json($ordersService->update($request, $orderId));
    }

    public function updateStatus(
        int $orderId,
        OrdersUpdateStatusRequest $request,
        OrdersService $ordersService
    ): void {
        $ordersService->updateStatus($request, $orderId);
    }
}
