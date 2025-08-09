<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->numerify('############'), // 12 digits to fit in 13 character limit
            'address' => fake()->address(),
            'balance' => fake()->numberBetween(0, 1000000),
            'debt' => fake()->numberBetween(0, 500000),
            'username' => fake()->unique()->userName(),
            'password' => Hash::make('password'),
        ];
    }
}
