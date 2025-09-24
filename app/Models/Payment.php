<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'payments';

    protected $fillable = [
        'order_id',
        'paymentmethod',
        'amount',
        'paymentdate',
    ];

    protected $casts = [
        '_id' => 'string',
        'order_id' => 'string',
    ];

    // Each payment belongs to one order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', '_id');
    }
}
