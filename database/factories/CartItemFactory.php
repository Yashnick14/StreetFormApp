<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        return [
            'cart_id'     => Cart::factory(),
            'product_id'  => Product::factory(),
            'quantity'    => fake()->numberBetween(1, 5),
            'size'        => fake()->randomElement(['XS','S','M','L','XL', null]),
            'unitprice'   => fake()->randomFloat(2, 5, 500),
        ];
    }
}
