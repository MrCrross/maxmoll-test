<?php

namespace Database\Seeders;

use App\Models\Orders\OrderItem;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Warehouses\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'root@root.com',
            'password' => Hash::make('qweqwe123'),
        ]);

        Stock::factory(10)->create();
        OrderItem::factory(10)->create();
    }
}
