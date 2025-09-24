<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'orderdate'   => now()->subDays(fake()->numberBetween(0, 30)),
            'orderstatus' => fake()->randomElement(['pending','paid','shipped','completed','cancelled']),
            'totalprice'  => fake()->randomFloat(2, 20, 2000),
        ];
    }
}
