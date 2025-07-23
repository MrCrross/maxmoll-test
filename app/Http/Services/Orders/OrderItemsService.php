<?php

namespace App\Http\Services\Orders;

use App\Exceptions\Orders\OrderLargeStockException;
use App\Http\Repositories\Orders\OrderItemsRepository;

class OrderItemsService
{
    /**
     * @param int $orderId
     * @param array $products - [$productId => $orderCount]
     * @return void
     */
    public function create(
        int $orderId,
        array $products,
    ): void {
        $orderItemsFields = [];
        foreach ($products as $id => $count) {
            $orderItemsFields[] = [
                'order_id' => $orderId,
                'product_id' => $id,
                'count' => $count,
            ];
        }
        OrderItemsRepository::insert($orderItemsFields);
    }

    /**
     * @param int $orderId
     * @param int $warehouseId
     * @param array $products
     * @return void
     * @throws OrderLargeStockException
     */
    public function changed(
        int $orderId,
        int $warehouseId,
        array $products
    ): void {
        $ordersStocksService = new OrdersStocksService();
        $orderItems = OrderItemsRepository::getByOrderId($orderId);
        $currentProducts = $orderItems->pluck('count', 'product_id')->toArray();
        [$createProducts, $updateProducts, $deleteProducts, $reduceFromStock, $returnInStock] = $this->getDiffProducts($currentProducts, $products);
        $ordersStocksService->checkAndReduceStock(
            $orderId,
            $warehouseId,
            $reduceFromStock
        );
        if (!empty($returnInStock)) {
            $ordersStocksService->returnInStock($orderId, $warehouseId, $returnInStock);
        }
        if (!empty($createProducts)) {
            $this->create($orderId, $createProducts);
        }
        if (!empty($updateProducts)) {
            foreach ($updateProducts as $productId => $count) {
                OrderItemsRepository::updateByProductId(
                    orderId: $orderId,
                    productId: $productId,
                    fields: [
                        'count' => $count
                    ]
                );
            }
        }
        if (!empty($deleteProducts)) {
            OrderItemsRepository::deleteByProductsIds($orderId, $deleteProducts);
        }
    }

    /**
     * Собираем изменения для проверки места на складе
     * @param array $currentProducts - [$productId => $orderCount]
     * @param array $newProducts - [$productId => $orderCount]
     * @return array - [$createProducts, $updateProducts, $deleteProducts, $reduceFromStock, $returnInStock]
     */
    private function getDiffProducts(array $currentProducts, array $newProducts): array
    {
        $createProducts = [];
        $updateProducts = [];
        $deleteProducts = [];
        $reduceFromStock = [];
        $returnInStock = [];

        foreach ($newProducts as $id => $count) {
            if (!isset($currentProducts[$id])) {
                $createProducts[$id] = $count;
                $reduceFromStock[$id] = $count;
            } else if ($count === 0) {
                $deleteProducts[] = $id;
                $returnInStock[$id] = $count;
            } else if ($currentProducts[$id] < $count) {
                $reduceFromStock[$id] = $count - $currentProducts[$id];
                $updateProducts[$id] = $count;
            } else if ($currentProducts[$id] > $count) {
                $updateProducts[$id] = $count;
                $returnInStock[$id] = $currentProducts[$id] - $count;
            }
        }
        foreach ($currentProducts as $id => $count) {
            if (!isset($newProducts[$id])) {
                $deleteProducts[] = $id;
                $returnInStock[$id] = $count;
            }
        }

        return [$createProducts, $updateProducts, $deleteProducts, $reduceFromStock, $returnInStock];
    }
}
