<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goods>
 */
class GoodsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'stock' => fake()->numberBetween(0, 100),
            'unit' => fake()->randomElement(['pcs', 'kg', 'liter', 'm', 'm2', 'm3']),
            'cost' => fake()->numberBetween(10000, 100000),
            'price' => fake()->numberBetween(15000, 150000),
        ];
    }
}
