<?php

namespace Database\Factories\Orders;

use App\Models\Warehouses\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orders\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-3 months', 'now');
        $status = $this->faker->randomElement(['active', 'canceled', 'completed']);

        return [
            'customer' => $this->faker->name(),
            'created_at' => $createdAt,
            'warehouse_id' => $this->faker->numberBetween(1, 10),
            'status' => $status,
            'completed_at' => $status === 'completed' ? $this->faker->dateTimeBetween($createdAt, 'now'): null,
        ];
    }
}
