<?php

namespace App\Console\Commands;

use App\Models\Warehouses\Stock;
use Illuminate\Console\Command;

class FakeGenerateGuides extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fake:guides {--count=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate fake data on guides(products, warehouses, stock)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int)$this->option('count');
        if ($count <= 100) {
            Stock::factory($count)->create();
        } else {
            echo 'Number of lines is very much! Max count is 100.' . PHP_EOL;
        }
    }
}
