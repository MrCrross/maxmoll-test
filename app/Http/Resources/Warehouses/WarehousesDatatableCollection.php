<?php

namespace App\Http\Resources\Warehouses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehousesDatatableCollection extends ResourceCollection
{
    public function __construct(
        mixed $resource,
        private readonly int $count,
    ){
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'per_page' => $this->count,
        ];
    }
}
