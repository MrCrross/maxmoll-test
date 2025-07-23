<?php

namespace Database\Factories\Warehouses;

use App\Models\Product;
use App\Models\Warehouses\Stock;
use App\Models\Warehouses\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StockFactory extends Factory
{
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'warehouse_id' => Warehouse::factory(),
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
