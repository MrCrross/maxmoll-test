<?php

namespace App\Http\Resources\Warehouses;

use App\Models\Warehouses\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StocksDatatableCollection extends ResourceCollection
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
            'data' => $this->collection->map(static function (Stock $stock) {
                return [
                    'id' => $stock->id,
                    'product' => $stock->product,
                    'warehouse' => $stock->warehouse,
                    'stock' => $stock->stock,
                ];
            }),
            'per_page' => $this->count,
        ];
    }
}
