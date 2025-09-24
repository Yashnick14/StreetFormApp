<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class OrderItem extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'orderquantity',
        'ordersize',
    ];

    protected $casts = [
        '_id' => 'string',
        'order_id' => 'string',
    ];

    // M → 1 Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', '_id');
    }

    // M → 1 Product (from MySQL)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
