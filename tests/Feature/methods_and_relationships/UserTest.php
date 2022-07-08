<?php

namespace Tests\Feature\methods_and_relationships;

use App\Enums\UserRole;
use App\Models\Fridge;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testFridges()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $this->assertEquals($fridge->id, $user->fridges()->first()->id);
    }

    public function testManagedFridges()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $this->assertEquals($fridge->id, $user->managedFridges()->first()->id);
    }

    public function testOwnFridges()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $fridge2 = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $this->assertEquals($fridge->id, $user->ownFridges()->first()->id);
        $this->assertNotEquals($fridge2->id, $user->ownFridges()->first()->id);
    }

    public function testIsFridgeManager()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $this->assertTrue($user->isFridgeManager($fridge));
    }

    public function testIsFridgeUser()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $this->assertTrue($user->isFridgeUser($fridge));
    }

    public function testIsFridgeUserNoOwner()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $this->assertTrue($user->isFridgeUserNoOwner($fridge));
        $this->assertFalse($user2->isFridgeUserNoOwner($fridge));
    }

    public function testIsActualRank()
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $this->assertTrue($user->isActualRank(UserRole::USER));
        $this->assertFalse($user->isActualRank(UserRole::ADMIN));
    }

    public function testIsFridgeOwner()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $this->assertTrue($user->isFridgeOwner($fridge));
        $fridge2 = Fridge::factory()->create(['owner_id' => $user2->id]);
        $this->assertFalse($user->isFridgeOwner($fridge2));
    }

    public function testIsPermittedToManage()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user3->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $this->assertTrue($user3->isFridgeManager($fridge));
        $this->assertTrue($user3->isPermittedToManage($fridge));
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $this->assertTrue($user->isPermittedToManage($fridge));
        $user2->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $this->assertFalse($user2->isPermittedToManage($fridge));
        $user2->fridges()->detach();
    }

    public function testIsAdmin()
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->assertTrue($user->isAdmin());
        $user = User::factory()->create(['role' => UserRole::USER]);
        $this->assertFalse($user->isAdmin());
    }
}
