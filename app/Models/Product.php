<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'size',
        'price',
        'stockquantity',
        'category_id',
        'type',
        'image',
        'image2',
        'image3',
        'image4',
    ];

    protected $casts = [
        'stockquantity' => 'array', 
    ];

    // 1 Product → M CartItem
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // 1 Product → M WishlistItem
    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    // 1 Product → M OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // M Product → 1 Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}