<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CartItem extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cart_items';
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'size', 'unitprice'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', '_id');
    }

    // 🚫 Remove product() relation since we load manually in Livewire
}
