<?php

namespace App\Models\Orders;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $fillable = [
        'order_id',
        'product_id',
        'count',
    ];
    public $timestamps = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            related: Product::class,
            foreignKey: 'product_id',
            ownerKey: 'id'
        )->select(
            'id',
            'name',
            'price',
        );
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            related: Order::class,
            foreignKey: 'order_id',
            ownerKey: 'id'
        )->select(
            'id',
            'customer',
            'warehouse_id',
            'status',
            'created_at',
            'completed_at',
        );
    }
}
