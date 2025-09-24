<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'product_id'  => Product::factory(),
            'title'       => fake()->sentence(6),
            'comments'    => fake()->paragraph(),
            'rating'      => fake()->numberBetween(1, 5),
        ];
    }
}
