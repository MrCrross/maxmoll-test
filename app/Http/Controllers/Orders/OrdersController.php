<?php

namespace App\Http\Controllers\Orders;

use App\Exceptions\Orders\OrderChangeOnActiveException;
use App\Exceptions\Orders\OrderChangeOnCanceledException;
use App\Exceptions\Orders\OrderChangeOnCompletedException;
use App\Exceptions\Orders\OrderLargeStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrdersCreateRequest;
use App\Http\Requests\Orders\OrdersDatatableRequest;
use App\Http\Requests\Orders\OrdersUpdateRequest;
use App\Http\Requests\Orders\OrdersUpdateStatusRequest;
use App\Http\Services\Orders\OrdersService;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersController extends Controller
{
    /**
     * @param OrdersDatatableRequest $request
     * @param OrdersService $ordersService
     * @return JsonResource
     */
    public function datatable(
        OrdersDatatableRequest $request,
        OrdersService $ordersService
    ): JsonResource {
        return $ordersService->datatable($request);
    }

    /**
     * @param OrdersCreateRequest $request
     * @param OrdersService $ordersService
     * @return JsonResource
     * @throws OrderLargeStockException
     */
    public function create(
        OrdersCreateRequest $request,
        OrdersService $ordersService
    ): JsonResource {
        return $ordersService->create($request);
    }

    /**
     * @param int $orderId
     * @param OrdersUpdateRequest $request
     * @param OrdersService $ordersService
     * @return JsonResource
     * @throws OrderLargeStockException
     */
    public function update(
        int $orderId,
        OrdersUpdateRequest $request,
        OrdersService $ordersService
    ): JsonResource {
        return $ordersService->update($request, $orderId);
    }

    /**
     * @param int $orderId
     * @param OrdersUpdateStatusRequest $request
     * @param OrdersService $ordersService
     * @return void
     * @throws OrderLargeStockException
     * @throws OrderChangeOnActiveException
     * @throws OrderChangeOnCanceledException
     * @throws OrderChangeOnCompletedException
     */
    public function updateStatus(
        int $orderId,
        OrdersUpdateStatusRequest $request,
        OrdersService $ordersService
    ): void {
        $ordersService->updateStatus($request, $orderId);
    }
}
