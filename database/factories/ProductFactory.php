<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $cat = \App\Models\Category::inRandomOrder()->first();

        return [
            'name'           => ucfirst(fake()->words(3, true)),
            'description'    => fake()->paragraph(),
            'size'           => fake()->randomElement(['XS','S','M','L','XL', null]),
            'price'          => fake()->randomFloat(2, 5, 500),
            'stockquantity'  => json_encode([
                'XS' => fake()->numberBetween(0, 50),
                'S'  => fake()->numberBetween(0, 50),
                'M'  => fake()->numberBetween(0, 50),
                'L'  => fake()->numberBetween(0, 50),
                'XL' => fake()->numberBetween(0, 50),
            ]),
            'image'          => fake()->imageUrl(800, 800, 'fashion', true), // Single image
            'category_id'    => optional($cat)->id,
            'type'           => fake()->randomElement(['Hoodies','Cargo Pants','Sweatshirts','T-Shirts','Jackets']),
        ];
    }
}