<?php

namespace App\Models\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Models\Product;
use App\Models\Warehouses\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'customer',
        'warehouse_id',
        'status',
        'created_at',
        'completed_at',
    ];
    public $timestamps = false;

    protected $casts = [
        'customer' => 'string',
        'warehouse_id' => 'integer',
        'status' => OrderStatusEnum::class,
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(
            Warehouse::class,
            'warehouse_id',
            'id'
        )->select(
            'id',
            'name',
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'order_id',
            'id'
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'order_items',
            'order_id',
            'product_id'
        );
    }
}
