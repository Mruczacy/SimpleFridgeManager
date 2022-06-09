<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    public function definition() : array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}
