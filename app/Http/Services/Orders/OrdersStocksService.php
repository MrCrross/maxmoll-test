<?php

namespace App\Http\Services\Orders;

use App\Exceptions\Orders\OrderLargeStockException;
use App\Http\Repositories\Products\ProductsRepository;
use App\Http\Repositories\Warehouses\StocksRepository;
use App\Http\Repositories\Warehouses\WarehousesRepository;
use App\Http\Services\Warehouses\HistoryStocksService;

class OrdersStocksService
{
    /**
     * @param int $orderId
     * @param int $warehouseId
     * @param array $products
     * @return void
     * @throws OrderLargeStockException
     */
    public function checkAndReduceStock(int $orderId, int $warehouseId, array $products): void
    {
        $this->checkProductsInWarehouse($warehouseId, $products);
        $this->reduceFromStock($orderId, $warehouseId, $products);
    }

    /**
     * @param int $warehouseId
     * @param array $products - [$productId => $orderCount]
     * @return void
     * @throws OrderLargeStockException
     */
    public function checkProductsInWarehouse(int $warehouseId, array $products): void
    {
        $productsIds = array_keys($products);
        $productsStocks = StocksRepository::getStocksProducts($warehouseId, $productsIds)->toArray();
        foreach ($products as $productId => $count) {
            if (
                !isset($productsStocks[$productId]) ||
                $count > $productsStocks[$productId]
            ) {
                throw new OrderLargeStockException(
                    $productsStocks[$productId] ?? 0,
                    ProductsRepository::getById($productId),
                    WarehousesRepository::getById($warehouseId)
                );
            }
        }
    }

    /**
     * @param int $orderId
     * @param int $warehouseId
     * @param array $products - [$productId => $productCount]
     * @return void
     */
    public function reduceFromStock(int $orderId, int $warehouseId, array $products): void
    {
        foreach ($products as $productId => $count) {
            StocksRepository::reduceStock($warehouseId, $productId, $count);
        }
        HistoryStocksService::create($orderId, $warehouseId, $products, false);
    }

    /**
     * @param int $orderId
     * @param int $warehouseId
     * @param array $products - [$productId => $productCount]
     * @return void
     */
    public function returnInStock(int $orderId, int $warehouseId, array $products): void
    {
        foreach ($products as $productId => $count) {
            StocksRepository::returnStock($warehouseId, $productId, $count);
        }
        HistoryStocksService::create($orderId, $warehouseId, $products);
    }
}
