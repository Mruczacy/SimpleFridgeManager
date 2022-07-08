<?php

namespace Tests\Feature\Methods_And_Relationships;

use App\Models\Fridge;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FridgeTest extends TestCase
{
    public function testOwner()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $this->assertEquals($user->id, $fridge->owner->id);
    }

    public function testProducts()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);
        $this->assertEquals($fridge->id, $product->fridge->id);
    }

    public function testManagers()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $manager = User::factory()->create();
        $fridge->managers()->attach($manager->id, ['is_manager' => true]);
        $this->assertEquals($manager->id, $fridge->managers->first()->id);
    }

    public function testUsers()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user = User::factory()->create();
        $fridge->users()->attach($user->id, ['is_manager' => false]);
        $this->assertEquals($user->id, $fridge->users->first()->id);
    }
}
