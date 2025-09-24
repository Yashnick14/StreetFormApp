<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id'       => Order::factory(),
            'product_id'     => Product::factory(),
            'orderquantity'  => fake()->numberBetween(1, 5),
            'ordersize'      => fake()->randomElement(['XS','S','M','L','XL', null]),
            'orderprice'     => fake()->randomFloat(2, 5, 500), // captured at purchase time
        ];
    }
}
