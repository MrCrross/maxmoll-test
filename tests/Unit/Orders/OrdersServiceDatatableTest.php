<?php

namespace Orders;

use App\Http\Repositories\Orders\OrdersRepository;
use App\Http\Resources\Orders\OrdersDatatableCollection;
use App\Http\Services\Orders\OrdersService;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Mockery;
use Tests\TestCase;

class OrdersServiceDatatableTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function it_returns_datatable_collection()
    {
        $pageCount = 15;
        $mockRepository = Mockery::mock(OrdersRepository::class);
        $mockRepository->shouldReceive('datatable')
            ->with($pageCount, '', [], [], [])
            ->andReturn(new Paginator(['id' => 1], $pageCount));

        $service = new OrdersService($mockRepository);
        $result = $service->datatable(new Request());

        $this->assertInstanceOf(OrdersDatatableCollection::class, $result);
    }

    /** @test */
    public function it_passes_correct_parameters_to_repository()
    {
        $mockRepository = Mockery::mock(OrdersRepository::class);

        $order = Mockery::mock(Order::class);
        $order->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $order->shouldReceive('getAttribute')->with('customer')->andReturn('John Doe');
        $order->shouldReceive('getAttribute')->with('status')->andReturn('active');
        $order->shouldReceive('getAttribute')->with('warehouse')->andReturn((object)['id' => 1, 'name' => 'Main Warehouse']);
        $order->shouldReceive('getAttribute')->with('created_at')->andReturn('2023-01-01 00:00:00');
        $order->shouldReceive('getAttribute')->with('completed_at')->andReturn(null);

        $item = Mockery::mock(OrderItem::class);
        $product = (object)['id' => 5, 'name' => 'Test Product', 'price' => 100];
        $item->shouldReceive('getAttribute')->with('product')->andReturn($product);
        $item->shouldReceive('getAttribute')->with('count')->andReturn(2);

        $order->shouldReceive('getAttribute')->with('items')->andReturn(collect([$item]));

        $expectedPaginator = new Paginator([$order], 20);

        $mockRepository->shouldReceive('datatable')
            ->once()
            ->withArgs(function($count, $customer, $statuses, $warehouses, $products) {
                return $count === 20
                    && $customer === 'John Doe'
                    && $statuses === ['active']
                    && $warehouses === [1, 2]
                    && $products === [5];
            })
            ->andReturn($expectedPaginator);

        $request = new Request([
            'page_count' => 20,
            'customer' => 'John Doe',
            'statuses' => ['active'],
            'warehouses' => [1, 2],
            'products' => [5],
        ]);

        $service = new OrdersService($mockRepository);
        $result = $service->datatable($request);

        $this->assertInstanceOf(OrdersDatatableCollection::class, $result);
        $response = $result->toArray(new Request());

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('per_page', $response);
        $this->assertEquals(20, $response['per_page']);

        $orderData = $response['data'][0];
        $this->assertArrayHasKey('id', $orderData);
        $this->assertArrayHasKey('customer', $orderData);
        $this->assertArrayHasKey('status', $orderData);
        $this->assertArrayHasKey('products', $orderData);
        $this->assertArrayHasKey('warehouse', $orderData);
        $this->assertArrayHasKey('created_at', $orderData);
        $this->assertArrayHasKey('completed_at', $orderData);

        $productData = $orderData['products'][0];
        $this->assertArrayHasKey('id', $productData);
        $this->assertArrayHasKey('name', $productData);
        $this->assertArrayHasKey('price', $productData);
        $this->assertArrayHasKey('count', $productData);
    }

    /** @test */
    public function it_uses_default_values_when_no_query_params()
    {
        $mockRepository = Mockery::mock(OrdersRepository::class);
        $expectedPaginator = new Paginator([], 15);

        $mockRepository->shouldReceive('datatable')
            ->once()
            ->with(15, '', [], [], [])
            ->andReturn($expectedPaginator);

        $request = new Request();
        $service = new OrdersService($mockRepository);
        $result = $service->datatable($request);

        $response = $result->toArray(new Request());

        $this->assertInstanceOf(OrdersDatatableCollection::class, $result);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('per_page', $response);
        $this->assertEquals(15, $response['per_page']);
        $this->assertEmpty($response['data']);
    }
}
