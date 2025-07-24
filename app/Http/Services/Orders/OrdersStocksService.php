<?php

namespace App\Http\Services\Orders;

use App\Exceptions\Orders\OrderLargeStockException;
use App\Http\Repositories\Products\ProductsRepository;
use App\Http\Repositories\Warehouses\StocksRepository;
use App\Http\Repositories\Warehouses\WarehousesRepository;
use App\Http\Services\Warehouses\HistoryStocksService;

class OrdersStocksService
{
    public function __construct(
        private stocksRepository $stocksRepository = new StocksRepository(),
        private HistoryStocksService $historyStocksService = new HistoryStocksService()
    )
    {
    }

    /**
     * @param int $orderId
     * @param int $warehouseId
     * @param array $products
     * @return void
     * @throws OrderLargeStockException
     */
    public function checkAndReduceStock(
        int $orderId,
        int $warehouseId,
        array $products
    ): void {
        $this->checkProductsInWarehouse($warehouseId, $products);
        $this->reduceFromStock($orderId, $warehouseId, $products);
    }

    /**
     * @param int $warehouseId
     * @param array $products - [$productId => $orderCount]
     * @param ProductsRepository $productsRepository
     * @param WarehousesRepository $warehousesRepository
     * @return void
     * @throws OrderLargeStockException
     */
    public function checkProductsInWarehouse(
        int $warehouseId,
        array $products,
        ProductsRepository $productsRepository = new ProductsRepository(),
        WarehousesRepository $warehousesRepository = new WarehousesRepository()
    ): void {
        $productsIds = array_keys($products);
        $productsStocks = $this->stocksRepository->getStocksProducts($warehouseId, $productsIds)->toArray();
        foreach ($products as $productId => $count) {
            if (
                !isset($productsStocks[$productId]) ||
                $count > $productsStocks[$productId]
            ) {
                throw new OrderLargeStockException(
                    $productsStocks[$productId] ?? 0,
                    $productsRepository->getById($productId),
                    $warehousesRepository->getById($warehouseId)
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
    public function reduceFromStock(
        int $orderId,
        int $warehouseId,
        array $products
    ): void {
        foreach ($products as $productId => $count) {
            $this->stocksRepository->reduceStock($warehouseId, $productId, $count);
        }
        $this->historyStocksService->create($orderId, $warehouseId, $products, false);
    }

    /**
     * @param int $orderId
     * @param int $warehouseId
     * @param array $products - [$productId => $productCount]
     * @return void
     */
    public function returnInStock(
        int $orderId,
        int $warehouseId,
        array $products
    ): void {
        foreach ($products as $productId => $count) {
            $this->stocksRepository->returnStock($warehouseId, $productId, $count);
        }
        $this->historyStocksService->create($orderId, $warehouseId, $products);
    }
}
