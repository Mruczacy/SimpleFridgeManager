<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class FridgeFactory extends Factory
{
    public function definition(): array
    {
        $throw = rand(1, 100);
        $asap = $throw + 2;
        $innear = $asap + 7;
        return [
            'name' => $this->faker->name(),
            'owner_id' => User::factory(),
            'throw_it_out_treshold' => $throw,
            'asap_treshold' => $asap,
            'in_near_future_treshold' => $innear
        ];
    }
}
