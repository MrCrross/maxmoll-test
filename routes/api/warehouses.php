<?php

use App\Http\Controllers\Warehouses\HistoryStocksController;
use App\Http\Controllers\Warehouses\StocksController;
use App\Http\Controllers\Warehouses\WarehousesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/', [WarehousesController::class, 'datatable']);
    Route::get('/stocks', [StocksController::class, 'datatable']);
    Route::get('/autocomplete', [WarehousesController::class, 'autocomplete']);
    Route::get('/stocks/history', [HistoryStocksController::class, 'datatable']);
});
