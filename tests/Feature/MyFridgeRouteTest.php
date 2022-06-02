<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Fridge;

class MyFridgeRouteTest extends TestCase
{

    use RefreshDatabase;
    public function testGuestCannotAccessIndexOwn(): void
    {
        $response = $this->get("/myfridges");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessIndexOwn(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/myfridges");
        $response->assertStatus(200);
    }

    public function testGuestCannotAccessEditOwn(): void
    {
        $response = $this->get("/myfridges/1/edit");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessEditOwnOnItsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->get("/myfridges/{$fridge->id}/edit");
        $response->assertStatus(200);
    }

    public function testUserCannotAccessEditOwnOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->actingAs($user)->get("/myfridges/{$fridge->id}/edit");
        $response->assertStatus(403);
    }

    public function testGuestCannotAccessUpdateOwn(): void
    {
        $response = $this->put("/myfridges/1");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessUpdateOwnOnItsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->put("/myfridges/{$fridge->id}", [
            'name' => 'test',
        ]);
        $fridge2 = Fridge::find($fridge->id);
        $this->assertFalse($fridge2->name == $fridge->name);
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
    }

    public function testUserCannotAccessUpdateOwnOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->actingAs($user)->put("/myfridges/{$fridge->id}", [
            'name' => 'test',
        ]);
        $response->assertStatus(403);
    }

    public function testGuestCannotAccessDestroyOwn(): void
    {
        $response = $this->delete("/myfridges/1");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessDestroyOwnOnItsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create([
            'owner_id' => $user->id,
        ]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->delete("/myfridges/{$fridge->id}");
        $this->assertNull(Fridge::find($fridge->id));
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
    }

    public function testUserCannotAccessDestroyOwnOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->actingAs($user)->delete("/myfridges/{$fridge->id}");
        $response->assertStatus(403);
    }
}
?>
