<?php

namespace App\Http\Services\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Exceptions\Orders\OrderChangeOnActiveException;
use App\Exceptions\Orders\OrderChangeOnCanceledException;
use App\Exceptions\Orders\OrderChangeOnCompletedException;
use App\Exceptions\Orders\OrderLargeStockException;
use App\Http\Repositories\Orders\OrdersRepository;
use Illuminate\Support\Carbon;

class OrdersStatusService
{
    /**
     * @param int $orderId
     * @return void
     * @throws OrderChangeOnActiveException
     * @throws OrderLargeStockException
     */
    public function orderOnActive(int $orderId): void
    {
        $order = OrdersRepository::getWithStocksById($orderId);
        switch ($order->status) {
            case OrderStatusEnum::Completed:
                throw new OrderChangeOnActiveException();
                break;
            case OrderStatusEnum::Canceled:
                $ordersStocksService = new OrdersStocksService();
                $orderProducts = [];
                foreach ($order->items as $item) {
                    $orderProducts[$item->product->id] = $item->count;
                }
                $ordersStocksService->checkAndReduceStock(
                    $orderId,
                    $order->warehouse_id,
                    $orderProducts
                );
                break;
        }
        OrdersRepository::update(
            $orderId,
            [
                'status' => OrderStatusEnum::Active->value,
                'completed_at' => null,
            ]
        );
    }

    /**
     * @param int $orderId
     * @return void
     * @throws OrderChangeOnCanceledException
     */
    public function orderOnCanceled(int $orderId): void
    {
        $order = OrdersRepository::getWithStocksById($orderId);
        switch ($order->status) {
            case OrderStatusEnum::Completed:
                throw new OrderChangeOnCanceledException();
                break;
            case OrderStatusEnum::Active:
                $orderProducts = [];
                foreach ($order->items as $item) {
                    $orderProducts[$item->product->id] = $item->count;
                }
                new OrdersStocksService()->returnInStock($orderId, $order->warehouse_id, $orderProducts);
                break;
        }
        OrdersRepository::update(
            $orderId,
            [
                'status' => OrderStatusEnum::Canceled->value,
                'completed_at' => null,
            ]
        );
    }

    /**
     * @param int $orderId
     * @return void
     * @throws OrderChangeOnCompletedException
     */
    public function orderOnCompleted(int $orderId): void
    {
        $order = OrdersRepository::getWithStocksById($orderId);
        if ($order->status === OrderStatusEnum::Canceled) {
            throw new OrderChangeOnCompletedException();
        }
        OrdersRepository::update(
            $orderId,
            [
                'status' => OrderStatusEnum::Completed->value,
                'completed_at' => Carbon::now()->toDateTimeString(),
            ]
        );
    }
}
