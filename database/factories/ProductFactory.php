<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use App\Models\Fridge;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'expiration_date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'fridge_id' => Fridge::factory(),
            'product_category_id' => ProductCategory::factory(),
        ];
    }
}
