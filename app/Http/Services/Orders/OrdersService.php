<?php

namespace App\Http\Services\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Exceptions\Orders\OrderChangeOnActiveException;
use App\Exceptions\Orders\OrderChangeOnCanceledException;
use App\Exceptions\Orders\OrderChangeOnCompletedException;
use App\Exceptions\Orders\OrderLargeStockException;
use App\Http\Repositories\Orders\OrdersRepository;
use App\Http\Resources\Orders\OrdersDatatableCollection;
use App\Http\Resources\Orders\OrdersResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class OrdersService
{
    /**
     * @param Request $request
     * @return JsonResource
     */
    public function datatable(Request $request): JsonResource
    {
        $count = $request->query('page_count', 15);
        $customer = $request->query('customer', '');
        $statuses = $request->query('statuses', []);
        $warehousesIds = $request->query('warehouses', []);
        $productsIds = $request->query('products', []);
        $datatable = OrdersRepository::datatable(
            count: $count,
            customer: $customer,
            statuses: $statuses,
            warehousesIds: $warehousesIds,
            productsIds: $productsIds
        );

        return new OrdersDatatableCollection($datatable, $count);
    }

    /**
     * @param Request $request
     * @return JsonResource
     * @throws OrderLargeStockException
     */
    public function create(Request $request): JsonResource
    {
        $ordersStocksService = new OrdersStocksService();
        $products = $this->productFormatting($request->post('products'));
        $ordersStocksService->checkProductsInWarehouse($request->post('warehouse_id'), $products);
        $orderFields = [
            'customer' => $request->post('customer'),
            'warehouse_id' => $request->post('warehouse_id'),
            'created_at' => Carbon::now()->toDateTimeString(),
            'status' => OrderStatusEnum::Active->value,
        ];
        $order = OrdersRepository::create($orderFields);
        new OrderItemsService()->create(
            $order->id,
            $products
        );
        $ordersStocksService->reduceFromStock(
            $order->id,
            $order->warehouse_id,
            $products
        );

        return new OrdersResource(OrdersRepository::getWithStocksById($order->id));
    }

    /**
     * @param Request $request
     * @param int $orderId
     * @return JsonResource
     * @throws OrderLargeStockException
     */
    public function update(
        Request $request,
        int $orderId
    ): JsonResource {
        $order = OrdersRepository::getById($orderId);
        $fields = [];
        if ($request->has('customer')) {
            $fields['customer'] = $request->post('customer');
        }
        if ($request->has('products')) {
            new OrderItemsService()->changed(
                $orderId,
                $order->getAttribute('warehouse_id'),
                $this->productFormatting($request->post('products')),
            );
        }
        if (!empty($fields)) {
            OrdersRepository::update($orderId, $fields);
        }

        return new OrdersResource(OrdersRepository::getWithStocksById($orderId));
    }

    /**
     * @param Request $request
     * @param int $orderId
     * @return void
     * @throws OrderChangeOnActiveException
     * @throws OrderLargeStockException
     * @throws OrderChangeOnCanceledException
     * @throws OrderChangeOnCompletedException
     */
    public function updateStatus(
        Request $request,
        int $orderId,
    ): void {
        $orderStatusService = new OrdersStatusService();
        switch ($request->post('status')) {
            case OrderStatusEnum::Active->value:
                $orderStatusService->orderOnActive($orderId);
                break;
            case OrderStatusEnum::Canceled->value:
                $orderStatusService->orderOnCanceled($orderId);
                break;
            case OrderStatusEnum::Completed->value:
                $orderStatusService->orderOnCompleted($orderId);
                break;
        }
    }

    /**
     * @param array $products - [['id' => $productId, 'count' => $orderCount]]
     * @return array
     */
    private function productFormatting(array $products): array
    {
        $result = [];
        foreach ($products as $product) {
            if (is_array($product)) {
                $product = (object)$product;
            }
            $id = (int)$product->id;
            $count = (int)$product->count;
            if (isset($result[$id])) {
                $result[$id] += $count;
            } else {
                $result[$id] = $count;
            }
        }

        return $result;
    }
}
