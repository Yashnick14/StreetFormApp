<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id'      => Order::factory(),
            'paymentmethod' => fake()->randomElement(['card','cash','bank','paypal']),
            'amount'        => fake()->randomFloat(2, 20, 2000),
            'paymentdate'   => now()->subDays(fake()->numberBetween(0, 30)),
            'paymentstatus' => fake()->randomElement(['pending','paid','failed','refunded']),
        ];
    }
}
