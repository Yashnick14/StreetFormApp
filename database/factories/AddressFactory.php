<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'street'      => fake()->streetAddress(),
            'city'        => fake()->city(),
            'province'    => fake()->state(),
            'postalcode'  => fake()->postcode(),
            'country'     => fake()->country(),
        ];
    }
}
