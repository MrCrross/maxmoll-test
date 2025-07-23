<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Services\Products\ProductsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * @param Request $request
     * @param ProductsService $productsService
     * @return JsonResponse
     */
    public function autocomplete(
        Request $request,
        ProductsService $productsService
    ): JsonResponse {
        return response()->json($productsService->autocomplete($request));
    }
}
