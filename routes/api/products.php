<?php

use App\Http\Controllers\Products\ProductsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/autocomplete', [ProductsController::class, 'autocomplete']);
});
