<?php

namespace App\Http\Services\Warehouses;

use App\Http\Repositories\Warehouses\HistoryStocksRepository;
use App\Http\Resources\Warehouses\HistoryStockDatatableCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class HistoryStocksService
{
    public function __construct(
        private HistoryStocksRepository $historyStocksRepository = new HistoryStocksRepository(),
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResource
     */
    public function datatable(Request $request): JsonResource
    {
        $count = $request->query('page_count', 15);
        $dateStart = $request->query('date_start', '');
        $dateEnd = $request->query('date_end', '');
        $warehousesIds = $request->query('warehouses', []);
        $productsIds = $request->query('products', []);
        $datatable = $this->historyStocksRepository->datatable(
            count: $count,
            dateStart: $dateStart,
            dateEnd: $dateEnd,
            warehousesIds: $warehousesIds,
            productsIds: $productsIds
        );

        return new HistoryStockDatatableCollection($datatable, $count);
    }

    /**
     * @param int $orderId
     * @param int $warehouseId
     * @param array $products
     * @param bool $add
     * @return void
     */
    public function create(int $orderId, int $warehouseId, array $products, bool $add = true): void
    {
        $fields = [];
        $symbol = $add ? '+' : '-';
        $now = Carbon::now()->toDateTimeString();
        foreach ($products as $productId => $count) {
            $fields[] = [
                'order_id' => $orderId,
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'quantity' => $symbol . $count,
                'created_at' => $now,
            ];
        }

        $this->historyStocksRepository->create($fields);
    }
}
