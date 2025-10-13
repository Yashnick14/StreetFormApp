<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 Customer → 1 Cart
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // 1 Customer → 1 Wishlist
    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }

    // 1 Customer → M Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
