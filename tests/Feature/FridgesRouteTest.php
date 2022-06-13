<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Fridge;

class FridgesRouteTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestAndUserCannotAccessFridgesList(): void
    {
            $response = $this->get(route('fridges.index'));
            $response->assertRedirect(route('login'));
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->get(route('fridges.index'));
            $response->assertForbidden();
    }

    public function testAdminCanAccessFridgesList(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get(route('fridges.index'));
        $response->assertOk();
    }

    public function testGuestCannotAccessFridgesCreate(): void
    {
        $response = $this->get(route('fridges.create'));
        $response->assertRedirect(route('login'));
    }

    public function testUserAndAdminCanAccessFridgesCreate(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route('fridges.create'));
        $response->assertOk();
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get(route('fridges.create'));
        $response->assertOk();
    }

    public function testGuestCannotAccessFridgesShow(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $response = $this->get(route("fridges.show", $fridge->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessFridgesShow(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->actingAs($user)->get(route("fridges.show", $fridge->id));
        $response->assertForbidden();
    }

    public function testAdminCanAccessFridgesShow(): void
    {
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get(route("fridges.show", $fridge->id));
        $response->assertOk();
    }

    public function testGuestAndUserCannotAccessFridgesEdit(): void
    {
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->get(route("fridges.edit", $fridge->id));
        $response->assertRedirect(route('login'));
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route("fridges.edit", $fridge->id));
        $response->assertForbidden();
    }

    public function testAdminCanAccessFridgesEdit(): void
    {
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get(route("fridges.edit", $fridge->id));
        $response->assertOk();
    }

    public function testGuestAndUserCannotDestroyFridges(): void
    {
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->delete(route("fridges.destroy", $fridge->id));
        $response->assertRedirect(route('login'));
        $user = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->actingAs($user)->delete(route("fridges.destroy", $fridge->id));
        $response->assertForbidden();
    }

    public function testAdminCanDestroyFridges(): void
    {
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->delete(route("fridges.destroy", $fridge->id));
        $this->assertNull(Fridge::find($fridge->id));
        $response->assertRedirect(route('fridges.index'));
    }

    public function testGuestAndUsersCannotUpdateFridges(): void
    {
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->put(route('fridges.update', $fridge->id), [
            'name' => 'test',
        ]);
        $response->assertRedirect(route('login'));
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->put(route('fridges.update', $fridge->id), [
            'name' => 'test',
        ]);
        $response->assertForbidden();
    }

    public function testAdminCanUpdateFridges(): void
    {
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->put(route('fridges.update', $fridge->id), [
            'name' => 'test',
        ]);
        $fridge2 = Fridge::find($fridge->id);
        $this->assertFalse($fridge2->name == $fridge->name);
        $response->assertRedirect(route('fridges.index'));
    }

    public function testGuestCannotAccessStoreFridges(): void
    {
        $response = $this->post(route('fridges.store'), [
            'name' => 'test',
        ]);
        $response->assertRedirect(route('login'));
    }

    public function testUserCanStoreFridges(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->post(route('fridges.store'), [
            'name' => 'test',
        ]);
        $response->assertRedirect(route('myfridges.indexOwn'));
        $this->assertTrue($user->fridges->contains('name', 'test'));
        $this->assertNotNull(Fridge::find($user->fridges->first()->value('id')));
    }
}
