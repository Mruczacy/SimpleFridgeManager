<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Fridge;

class ManagementsRouteTest extends TestCase

{

    public function testGuestCannotAttachFridgeToSb()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->post("/manage/attach/{$fridge->id}/{$user->id}", [
            'is_owner' => 1
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $user->delete();
        $fridge->delete();
    }

    public function testNoOwnerUserCannotAttachFridgeToSb()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_owner' => 0]);
        $response = $this->actingAs($user)->post("/manage/attach/{$fridge->id}/{$user->id}", [
            'is_owner' => 1,
        ]);
        $user->fridges()->detach();
        $response->assertStatus(403);
        $user->delete();
        $fridge->delete();
    }

    public function testOwnerUserCanAttachFridgeToSb()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_owner' => 1]);

        $response = $this->actingAs($user)->post("/manage/attach/{$fridge->id}/{$user2->id}", [
            'is_owner' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $this->assertTrue($user2->fridges->contains($fridge->id));
        $user2->fridges()->detach();
        $user->fridges()->detach();
        $user->delete();
        $user2->delete();
        $fridge->delete();
    }

    public function testGuestCannotDetachFridgeFromSb()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $response = $this->delete("/manage/detach/{$fridge->id}/{$user->id}");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $user->delete();
        $fridge->delete();
    }

    public function testNoOwnerUserCannotDetachFridgeFromSb()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_owner' => 0]);
        $response = $this->actingAs($user)->delete("/manage/detach/{$fridge->id}/{$user->id}");
        $user->fridges()->detach();
        $response->assertStatus(403);
        $user->delete();
        $fridge->delete();
    }

    public function testOwnerUserCanDetachFridgeFromSb()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_owner' => 1]);
        $user2->fridges()->attach($fridge->id, ['is_owner' => 0]);

        $response = $this->actingAs($user)->delete("/manage/detach/{$fridge->id}/{$user2->id}");

        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $this->assertFalse($user2->fridges->contains($fridge->id));
        $user->fridges()->detach();
        $user2->fridges()->detach();
        $user->delete();
        $user2->delete();
        $fridge->delete();
    }

}

?>
