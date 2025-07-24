<?php

namespace Tests\Feature\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Http\Resources\Orders\OrdersDatatableCollection;
use App\Http\Services\Orders\OrdersService;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\Product;
use App\Models\Warehouses\Stock;
use App\Models\Warehouses\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class OrdersDatatableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Stock::factory(10)->create();
        OrderItem::factory(10)->create();
    }

    /** @test */
    public function it_returns_paginated_orders()
    {
        $request = new Request([
            'page_count' => 10,
        ]);

        $service = new OrdersService();
        $result = $service->datatable($request);

        $this->assertInstanceOf(OrdersDatatableCollection::class, $result);
        $this->assertCount(10, $result->collection);
    }

    /** @test */
    public function it_filters_by_customer()
    {
        $customerName = 'Test Customer';
        Order::factory()->create(['customer' => $customerName]);

        $request = new Request([
            'customer' => $customerName,
        ]);

        $service = new OrdersService();
        $result = $service->datatable($request);

        $this->assertTrue(
            $result->collection->every(fn($order) => str_contains($order['customer'], $customerName))
        );
    }

    /** @test */
    public function it_filters_by_statuses()
    {
        $status = OrderStatusEnum::Active->value;
        Order::query()->update(['status' => OrderStatusEnum::Canceled->value]);
        Order::factory()->count(3)->create(['status' => $status]);

        $request = new Request([
            'statuses' => [$status],
        ]);

        $service = new OrdersService();
        $result = $service->datatable($request);

        $this->assertTrue(
            $result->collection->every(fn($order) => $order['status'] === OrderStatusEnum::Active)
        );
    }

    /** @test */
    public function it_filters_by_warehouse()
    {
        $warehouse = Warehouse::factory()->create();
        $order = Order::factory()->create(['warehouse_id' => $warehouse->id]);

        $request = new Request([
            'warehouses' => [$warehouse->id],
        ]);

        $service = new OrdersService();
        $result = $service->datatable($request);

        $this->assertCount(1, $result->collection);
        $this->assertEquals($order->id, $result->collection->first()['id']);
    }

    /** @test */
    public function it_filters_by_products()
    {
        $product = Product::first();
        OrderItem::factory(3)->create(['product_id' => $product->id]);

        $request = new Request([
            'products' => [$product->id],
        ]);

        $service = new OrdersService();
        $result = $service->datatable($request);

        $this->assertTrue(
            $result->collection->contains(
                fn($order) => collect($order['products'])->contains('id', $product->id)
            )
        );
    }
}
