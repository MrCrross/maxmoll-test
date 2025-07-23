<?php

namespace App\Exceptions\Orders;

use App\Exceptions\JsonErrorException;
use App\Models\Product;
use App\Models\Warehouses\Warehouse;

class OrderLargeStockException extends JsonErrorException
{
    public function __construct(
        int $stock,
        Product $product,
        Warehouse $warehouse
    ) {
        parent::__construct(
            "The number of product \"{$product->getAttribute('name')}\" in the order is larger than the number of stock. The quantity on warehouse \"{$warehouse->getAttribute('name')}\" is {$stock}.",
            400,
        );

        $this->additionalResponseData = [
            'data' => [
                'stock' => $stock,
                'product_id' => $product->getAttribute('id'),
                'warehouse_id' => $warehouse->getAttribute('id'),
            ],
        ];
    }
}
