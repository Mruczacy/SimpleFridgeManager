<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class FridgeFactory extends Factory
{
    public function definition() : array
    {
        return [
            'name' => $this->faker->name(),
            'owner_id' => User::factory(),
        ];
    }
}
?>
