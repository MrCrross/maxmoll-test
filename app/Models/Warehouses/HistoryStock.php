<?php

namespace App\Models\Warehouses;

use App\Models\Orders\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryStock extends Model
{
    protected $table = 'history_stocks';
    protected $fillable = [
        'order_id',
        'product_id',
        'warehouse_id',
        'quantity',
        'created_at',
    ];
    public $timestamps = false;

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            related: Order::class,
            foreignKey: 'order_id',
            ownerKey: 'id'
        )->select(
            'id',
            'customer',
            'status',
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            related: Product::class,
            foreignKey: 'product_id',
            ownerKey: 'id'
        )->select(
            'id',
            'name'
        );
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(
            related: Warehouse::class,
            foreignKey: 'warehouse_id',
            ownerKey: 'id'
        )->select(
            'id',
            'name'
        );
    }
}
