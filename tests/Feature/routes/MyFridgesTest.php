<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Fridge;

class MyFridgesTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotAccessIndexOwn(): void
    {
        $response = $this->get(route('myfridges.indexOwn'));
        $response->assertRedirect(route('login'));
    }

    public function testUserCanAccessIndexOwn(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('myfridges.indexOwn'));
        $response->assertOk();
    }

    public function testGuestCannotAccessEditOwn(): void
    {
        $fridge = Fridge::factory()->create();
        $response = $this->get(route('myfridges.editOwn', $fridge->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCanAccessEditOwnOnItsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->get(route('myfridges.editOwn', $fridge->id));
        $response->assertOk();
    }

    public function testUserCannotAccessEditOwnOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->actingAs($user)->get(route('myfridges.editOwn', $fridge->id));
        $response->assertForbidden();
    }

    public function testGuestCannotAccessUpdateOwn(): void
    {
        $fridge = Fridge::factory()->create();
        $response = $this->put(route('myfridges.updateOwn', $fridge->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCanAccessUpdateOwnOnItsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->put(route('myfridges.updateOwn', $fridge->id), [
            'name' => 'test',
        ]);
        $fridge2 = Fridge::find($fridge->id);
        $this->assertFalse($fridge2->name == $fridge->name);
        $response->assertRedirect(route('myfridges.indexOwn'));
    }

    public function testUserCannotAccessUpdateOwnOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->actingAs($user)->put(route('myfridges.updateOwn', $fridge->id), [
            'name' => 'test',
        ]);
        $response->assertForbidden();
    }

    public function testGuestCannotAccessDestroyOwn(): void
    {
        $fridge = Fridge::factory()->create();
        $response = $this->delete(route('myfridges.destroyOwn', $fridge->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCanAccessDestroyOwnOnItsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create([
            'owner_id' => $user->id,
        ]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->delete(route('myfridges.destroyOwn', $fridge->id));
        $this->assertNull(Fridge::find($fridge->id));
        $response->assertRedirect(route('myfridges.indexOwn'));
    }

    public function testUserCannotAccessDestroyOwnOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->actingAs($user)->delete(route('myfridges.destroyOwn', $fridge->id));
        $response->assertForbidden();
    }
}
