<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . '_' . $this->faker->numberBetween(1, 99999),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'category_id' => Category::query()->inRandomOrder()->first()?->id ?? Category::factory(),
        ];
    }
}
