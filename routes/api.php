<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(base_path('routes/api/auth.php'));
Route::prefix('warehouses')->group(base_path('routes/api/warehouses.php'));
Route::prefix('orders')->group(base_path('routes/api/orders.php'));
Route::prefix('products')->group(base_path('routes/api/products.php'));
