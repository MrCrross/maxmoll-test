<?php

namespace Database\Factories\Orders;

use App\Models\Orders\Order;
use App\Models\Warehouses\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orders\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::factory()->create();
        $products = Stock::query()
            ->where('warehouse_id', '=', $order->warehouse_id)
            ->pluck('product_id');

        return [
            'order_id' => $order->id,
            'product_id' => $products->random(),
            'count' => $this->faker->numberBetween(1, 10),
        ];
    }
}
