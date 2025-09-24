<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'customer_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'house_number',
        'street',
        'city',
        'postal_code',
        'payment_method',
        'orderdate',
        'orderstatus',
        'totalprice',
    ];

    protected $casts = [
        '_id' => 'string',
    ];

    // 1 Order → M OrderItems
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', '_id');
    }

    // 1 Order → 1 Payment
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', '_id');
    }

}
