<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;
use App\Models\Fridge;

class FridgesRouteTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */

     public function testGuestAndUserCannotAccessFridgesList()
     {
            $response = $this->get("/fridges");

            $response->assertStatus(302);
            $response->assertRedirect("/login");
            $user = User::factory()->create(['role' => UserRole::USER]);

            $response = $this->actingAs($user)->get("/fridges");

            $response->assertStatus(403);

            $user->delete();
     }

        public function testAdminCanAccessFridgesList()
        {
            $user = User::factory()->create(['role' => UserRole::ADMIN]);

            $response = $this->actingAs($user)->get("/fridges");

            $response->assertStatus(200);

            $user->delete();
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

            $user->delete();

            $user = User::factory()->create(['role' => UserRole::ADMIN]);

            $response = $this->actingAs($user)->get("/fridges/create");

            $response->assertStatus(200);

            $user->delete();
        }

        public function testGuestCannotAccessFridgesShow()
        {
            $fridge= Fridge::factory()->create();
            $response = $this->get("/fridges/" . $fridge->id);

            $response->assertStatus(302);
            $response->assertRedirect("/login");
            $fridge->delete();
        }

        public function testUserAndAdminCanAccessFridgesShow()
        {
            $user = User::factory()->create(['role' => UserRole::USER]);

            $fridge= Fridge::factory()->create();

            $response = $this->actingAs($user)->get("/fridges/" . $fridge->id);

            $response->assertStatus(200);

            $user->delete();

            $user = User::factory()->create(['role' => UserRole::ADMIN]);

            $response = $this->actingAs($user)->get("/fridges/" . $fridge->id);

            $response->assertStatus(200);

            $user->delete();

            $fridge->delete();
        }

        public function testGuestAndUserCannotAccessFridgesEdit()
        {
            $fridge= Fridge::factory()->create();
            $response = $this->get("/fridges/" . $fridge->id . "/edit");

            $response->assertStatus(302);
            $response->assertRedirect("/login");
            $user = User::factory()->create(['role' => UserRole::USER]);

            $response= $this->actingAs($user)->get("/fridges/" . $fridge->id . "/edit");

            $response->assertStatus(403);

            $user->delete();

            $fridge->delete();
        }

        public function testAdminCanAccessFridgesEdit()
        {
            $fridge= Fridge::factory()->create();

            $user = User::factory()->create(['role' => UserRole::ADMIN]);

            $response = $this->actingAs($user)->get("/fridges/" . $fridge->id . "/edit");

            $response->assertStatus(200);

            $user->delete();

            $fridge->delete();
        }

        public function testGuestAndUserCannotDestroyFridges()
        {
            $fridge= Fridge::factory()->create();
            $response = $this->delete("/fridges/" . $fridge->id);

            $response->assertStatus(302);
            $response->assertRedirect("/login");
            $fridge->delete();

            $user = User::factory()->create(['role' => UserRole::USER]);

            $fridge= Fridge::factory()->create();

            $response = $this->actingAs($user)->delete("/fridges/" . $fridge->id);

            $response->assertStatus(403);

            $user->delete();

            $fridge->delete();
        }

        public function testAdminCanDestroyFridges()
        {
            $fridge= Fridge::factory()->create();

            $user = User::factory()->create(['role' => UserRole::ADMIN]);

            $response = $this->actingAs($user)->delete("/fridges/" . $fridge->id);

            $response->assertStatus(302);
            $response->assertRedirect("/fridges");
            $fridge->delete();

            $user->delete();
        }

        public function testGuestAndUsersCannotUpdateFridges()
        {
            $fridge= Fridge::factory()->create();
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
            $user->delete();
            $fridge->delete();
        }

        public function testAdminCanUpdateFridges()
        {
            $fridge= Fridge::factory()->create();

            $user = User::factory()->create(['role' => UserRole::ADMIN]);

            $response = $this->actingAs($user)->put("/fridges/" . $fridge->id, [
                'name' => 'test',
            ]);

            $response->assertStatus(302);
            $response->assertRedirect("/fridges");
            $fridge->delete();

            $user->delete();
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
            $response->assertRedirect("/fridges");
            $this->assertTrue($user->fridges->contains('name', 'test'));
            $fridge= Fridge::find($user->fridges->first()->id);
            $user->fridges()->detach();
            $fridge->delete();
            $user->delete();
        }

}

?>
