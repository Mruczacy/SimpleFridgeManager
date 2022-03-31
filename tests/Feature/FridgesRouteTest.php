<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Fridge;

class FridgesRouteTest extends TestCase
{
    use RefreshDatabase;
     public function testGuestAndUserCannotAccessFridgesList()
     {
            $response = $this->get("/fridges");
            $response->assertStatus(302);
            $response->assertRedirect("/login");
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->get("/fridges");
            $response->assertStatus(403);
     }

        public function testAdminCanAccessFridgesList()
        {
            $user = User::factory()->create(['role' => UserRole::ADMIN]);
            $response = $this->actingAs($user)->get("/fridges");
            $response->assertStatus(200);
        }

        public function testGuestCannotAccessFridgesCreate()
        {
            $response = $this->get("/fridges/create");
            $response->assertStatus(302);
            $response->assertRedirect("/login");
        }

        public function testUserAndAdminCanAccessFridgesCreate()
        {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->get("/fridges/create");
            $response->assertStatus(200);
            $user = User::factory()->create(['role' => UserRole::ADMIN]);
            $response = $this->actingAs($user)->get("/fridges/create");
            $response->assertStatus(200);
        }

        public function testGuestCannotAccessFridgesShow()
        {
            $user= User::factory()->create();
            $fridge= Fridge::factory()->create(['owner_id' => $user->id]);
            $response = $this->get("/fridges/{$fridge->id}");
            $response->assertStatus(302);
            $response->assertRedirect("/login");
        }

        public function testUserCannotAccessFridgesShow()
        {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $user2 = User::factory()->create();
            $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
            $response = $this->actingAs($user)->get("/fridges/{$fridge->id}");
            $response->assertStatus(403);
        }

        public function testAdminCanAccessFridgesShow()
        {
            $user2 = User::factory()->create();
            $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
            $user = User::factory()->create(['role' => UserRole::ADMIN]);
            $response = $this->actingAs($user)->get("/fridges/{$fridge->id}");
            $response->assertStatus(200);
        }

        public function testGuestAndUserCannotAccessFridgesEdit()
        {
            $user2 = User::factory()->create();
            $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
            $response = $this->get("/fridges/" . $fridge->id . "/edit");

            $response->assertStatus(302);
            $response->assertRedirect("/login");
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response= $this->actingAs($user)->get("/fridges/" . $fridge->id . "/edit");
            $response->assertStatus(403);
        }

        public function testAdminCanAccessFridgesEdit()
        {
            $user2 = User::factory()->create();
            $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
            $user = User::factory()->create(['role' => UserRole::ADMIN]);
            $response = $this->actingAs($user)->get("/fridges/" . $fridge->id . "/edit");
            $response->assertStatus(200);
        }

        public function testGuestAndUserCannotDestroyFridges()
        {
            $user2 = User::factory()->create();
            $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
            $response = $this->delete("/fridges/" . $fridge->id);
            $response->assertStatus(302);
            $response->assertRedirect("/login");
            $fridge->delete();
            $user = User::factory()->create(['role' => UserRole::USER]);
            $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
            $response = $this->actingAs($user)->delete("/fridges/" . $fridge->id);
            $response->assertStatus(403);
        }

        public function testAdminCanDestroyFridges()
        {
            $user2 = User::factory()->create();
            $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
            $user = User::factory()->create(['role' => UserRole::ADMIN]);
            $response = $this->actingAs($user)->delete("/fridges/" . $fridge->id);
            $this->assertNull(Fridge::find($fridge->id));
            $response->assertStatus(302);
            $response->assertRedirect("/fridges");
        }

        public function testGuestAndUsersCannotUpdateFridges()
        {
            $user2 = User::factory()->create();
            $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
            $response = $this->put("/fridges/" . $fridge->id, [
                'name' => 'test',
            ]);
            $response->assertStatus(302);
            $response->assertRedirect("/login");
            $user= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->put("/fridges/" . $fridge->id, [
                'name' => 'test',
            ]);
            $response->assertStatus(403);
        }

        public function testAdminCanUpdateFridges()
        {
            $user2 = User::factory()->create();
            $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
            $user = User::factory()->create(['role' => UserRole::ADMIN]);
            $response = $this->actingAs($user)->put("/fridges/" . $fridge->id, [
                'name' => 'test',
            ]);
            $fridge2= Fridge::find($fridge->id);
            $this->assertFalse($fridge2->name == $fridge->name);
            $response->assertStatus(302);
            $response->assertRedirect("/fridges");
        }

        public function testGuestCannotAccessStoreFridges()
        {
            $response = $this->post("/fridges");
            $response->assertStatus(302);
            $response->assertRedirect("/login");
        }

        public function testUserCanStoreFridges()
        {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->post("/fridges", [
                'name' => 'test',
            ]);
            $response->assertStatus(302);
            $response->assertRedirect("/myfridges");
            $this->assertTrue($user->fridges->contains('name', 'test'));
            $this->assertNotNull(Fridge::find($user->fridges->first()->id));
        }
}
?>
