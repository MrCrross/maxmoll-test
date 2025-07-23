<?php

namespace App\Http\Services\Warehouses;

use App\Http\Repositories\Warehouses\WarehousesRepository;
use App\Http\Resources\Warehouses\WarehousesDatatableCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class WarehousesService
{
    /**
     * @param Request $request
     * @return JsonResource
     */
    public function datatable(Request $request): JsonResource
    {
        $count = $request->query('page_count', 15);
        $term = $request->query('term', '');
        $datatable = WarehousesRepository::datatable($count, $term);

        return new WarehousesDatatableCollection($datatable, $count);
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function autocomplete(Request $request): Collection
    {
        return WarehousesRepository::autocomplete($request->query('term', ''));
    }
}
