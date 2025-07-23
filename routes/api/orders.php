<?php

use App\Http\Controllers\Orders\OrdersController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/', [OrdersController::class, 'datatable']);
    Route::post('/', [OrdersController::class, 'create']);
    Route::patch('/{id}', [OrdersController::class, 'update'])->whereNumber('id');
    Route::patch('/{id}/status', [OrdersController::class, 'updateStatus'])->whereNumber('id');
});
