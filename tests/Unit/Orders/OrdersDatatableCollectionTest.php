<?php

namespace Orders;

use App\Http\Resources\Orders\OrdersDatatableCollection;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\Product;
use App\Models\Warehouses\Stock;
use App\Models\Warehouses\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class OrdersDatatableCollectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Stock::factory(10)->create();
        OrderItem::factory(10)->create();
    }
    /**
     * @test
     */
    public function it_transforms_orders_correctly()
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $order = Order::factory()
            ->has(OrderItem::factory()->for($product), 'items')
            ->create(['warehouse_id' => $warehouse->id]);

        $collection = new OrdersDatatableCollection(collect([$order]), 15);

        $result = $collection->toArray(new Request());

        $this->assertArrayHasKey('data', $result);
        $this->assertCount(1, $result['data']);

        $transformedOrder = $result['data'][0];
        $this->assertEquals($order->id, $transformedOrder['id']);
        $this->assertEquals($order->customer, $transformedOrder['customer']);
        $this->assertEquals($product->id, $transformedOrder['products'][0]['id']);
        $this->assertEquals($warehouse->id, $transformedOrder['warehouse']['id']);
    }

    /**
     * @test
     */
    public function it_includes_pagination_count()
    {
        $collection = new OrdersDatatableCollection(collect(), 25);
        $result = $collection->toArray(new Request());

        $this->assertEquals(25, $result['per_page']);
    }
}
