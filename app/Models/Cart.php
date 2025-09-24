<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Cart extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'carts';
    protected $fillable = ['customer_id'];

    protected $casts = [
        '_id' => 'string',
        'customer_id' => 'string',   // 👈 Force string
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', '_id');
    }
}
