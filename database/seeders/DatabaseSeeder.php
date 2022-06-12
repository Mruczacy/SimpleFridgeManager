<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => env('APP_TEST_USERNAME'),
            'email' => env('APP_TEST_EMAIL'),
            'password' => bcrypt(env('APP_TEST_PASSWORD')),
            'role' => UserRole::ADMIN,
        ]);
    }
}
