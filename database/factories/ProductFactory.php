<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use App\Models\Fridge;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'expiration_date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'fridge_id' => Fridge::factory(),
            'product_category_id' => ProductCategory::factory(),
        ];
    }
}
?>
