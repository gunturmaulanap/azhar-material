<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = fake()->numberBetween(100000, 1000000);
        $discount = fake()->numberBetween(0, $total * 0.1);
        $grandTotal = $total - $discount;
        $bill = fake()->numberBetween($grandTotal, $grandTotal * 1.5);
        $returnAmount = $bill - $grandTotal;

        return [
            'user_id' => User::factory(),
            'customer_id' => Customer::factory(),
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->numerify('############'), // 12 digits to fit in 13 character limit
            'total' => $total,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'balance' => fake()->numberBetween(0, $grandTotal),
            'bill' => $bill,
            'return' => $returnAmount,
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']),
            'image' => null,
        ];
    }
}
