<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Fridge;

class MyFridgeRouteTest extends TestCase
{

    public function testGuestCannotAccessIndexOwn()
    {
        $response = $this->get("/myfridges");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessIndexOwn()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/myfridges");

        $response->assertStatus(200);

        $user->delete();
    }

    public function testGuestCannotAccessEditOwn()
    {
        $response = $this->get("/myfridges/1/edit");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessEditOwnOnItsFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_owner' => 1]);

        $response = $this->actingAs($user)->get("/myfridges/{$fridge->id}/edit");

        $response->assertStatus(200);

        $user->fridges()->detach();
        $user->delete();
        $fridge->delete();
    }

    public function testUserCannotAccessEditOwnOnSbsFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->actingAs($user)->get("/myfridges/{$fridge->id}/edit");

        $response->assertStatus(403);

        $user->delete();
        $fridge->delete();


    }

    public function testGuestCannotAccessUpdateOwn()
    {
        $response = $this->put("/myfridges/1");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessUpdateOwnOnItsFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_owner' => 1]);

        $response = $this->actingAs($user)->put("/myfridges/{$fridge->id}", [
            'name' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");

        $user->fridges()->detach();
        $user->delete();
        $fridge->delete();
    }

    public function testUserCannotAccessUpdateOwnOnSbsFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->actingAs($user)->put("/myfridges/{$fridge->id}", [
            'name' => 'test',
        ]);

        $response->assertStatus(403);

        $user->delete();
        $fridge->delete();
    }

    public function testGuestCannotAccessDestroyOwn()
    {
        $response = $this->delete("/myfridges/1");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessDestroyOwnOnItsFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_owner' => 1]);

        $response = $this->actingAs($user)->delete("/myfridges/{$fridge->id}");

        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $user->delete();
    }

    public function testUserCannotAccessDestroyOwnOnSbsFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->actingAs($user)->delete("/myfridges/{$fridge->id}");

        $response->assertStatus(403);
        $user->delete();
        $fridge->delete();
    }
}
?>