<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 1 Wishlist → M WishlistItem
    public function items()
    {
        return $this->hasMany(WishlistItem::class);
    }
}
