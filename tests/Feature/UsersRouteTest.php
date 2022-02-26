<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Enums\UserRole;
use App\Models\User;

class UsersRouteTest extends TestCase {

        use RefreshDatabase;

        public function testGuestCannotAccessIndex() {
            $response = $this->get("/users");

            $response->assertStatus(302);
            $response->assertRedirect("/login");
        }

        public function testUserCannotAccessIndex() {
            $user = User::factory()->create(['role' => UserRole::USER]);

            $response = $this->actingAs($user)->get("/users");

            $response->assertStatus(403);

            $user->delete();
        }

        public function testAdminCanAccessIndex() {
            $user = User::factory()->create(['role' => UserRole::ADMIN]);

            $response = $this->actingAs($user)->get("/users");

            $response->assertStatus(200);

            $user->delete();
        }

        public function testGuestCannotAccessShow() {
            $response = $this->get("/account");

            $response->assertStatus(302);
            $response->assertRedirect("/login");
        }

        public function testUserCanAccessShow() {
            $user = User::factory()->create(['role' => UserRole::USER]);

            $response = $this->actingAs($user)->get("/account");

            $response->assertStatus(200);

            $user->delete();
        }

        public function testGuestCannotAccessEdit() {
            $response = $this->get("/users/1/edit");

            $response->assertStatus(302);
            $response->assertRedirect("/login");
        }

        public function testUserCannotAccessEdit() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $user2= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->get("/account/{$user2->id}/edit");

            $response->assertStatus(403);

            $user->delete();
            $user2->delete();
        }

        public function testAdminCanAccessEdit(){
            $user = User::factory()->create(['role' => UserRole::ADMIN]);
            $user2= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->get("/users/{$user2->id}/edit");

            $response->assertStatus(200);

            $user->delete();
            $user2->delete();
        }

        public function testGuestCannotAccessEditOwn(){
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->get("/account/{$user->id}/edit");

            $response->assertStatus(302);
            $response->assertRedirect("/login");

            $user->delete();
        }

        public function testUserCanAccessEditOwn(){
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->get("/account/{$user->id}/edit");

            $response->assertStatus(200);

            $user->delete();
        }

        public function testUserCannotAccessEditOwnOnSb(){
            $user = User::factory()->create(['role' => UserRole::USER]);
            $user2= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->get("/account/{$user2->id}/edit");

            $response->assertStatus(403);

            $user->delete();
            $user2->delete();
        }

        public function testGuestCannotAccessUpdate() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->put("/users/{$user->id}", [
                'name' => 'test',
                'email' => $user->email
            ]);

            $response->assertStatus(302);
            $response->assertRedirect("/login");
        }

        public function testUserCannotAccessUpdate() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $user2= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->put("/users/{$user2->id}", [
                'name' => 'test',
                'email' => $user2->email
            ]);

            $response->assertStatus(403);

            $user->delete();
            $user2->delete();
        }

        public function testAdminCanAccessUpdate() {
            $user = User::factory()->create(['role' => UserRole::ADMIN]);
            $user2= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->put("/users/{$user2->id}", [
                'name' => 'test',
                'email' => $user2->email
            ]);

            $response->assertStatus(302);
            $response->assertRedirect("/users");

            $user->delete();
            $user2->delete();
        }

        public function testGuestCannotAccessUpdateOwn() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->put("/account/{$user->id}", [
                'name' => 'test',
                'email' => $user->email
            ]);

            $response->assertStatus(302);
            $response->assertRedirect("/login");

            $user->delete();
        }

        public function testUserCanAccessUpdateOwn() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->put("/account/{$user->id}", [
                'name' => 'test',
                'email' => $user->email
            ]);

            $response->assertStatus(302);
            $response->assertRedirect("/account");

            $user->delete();
        }

        public function testUserCannotAccessUpdateOwnOnSb() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $user2= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->put("/account/{$user2->id}", [
                'name' => 'test',
                'email' => $user2->email
            ]);

            $response->assertStatus(403);

            $user->delete();
            $user2->delete();
        }

        public function testGuestCannotAccessDestroy() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->delete("/users/{$user->id}");

            $response->assertStatus(302);
            $response->assertRedirect("/login");

            $user->delete();
        }

        public function testUserCannotAccessDestroy() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $user2= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->delete("/users/{$user2->id}");

            $response->assertStatus(403);

            $user->delete();
            $user2->delete();
        }

        public function testAdminCanAccessDestroy() {
            $user = User::factory()->create(['role' => UserRole::ADMIN]);
            $user2= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->delete("/users/{$user2->id}");

            $response->assertStatus(302);
            $response->assertRedirect("/users");

            $user->delete();
            $user2->delete();
        }

        public function testGuestCannotAccessDestroyOwn() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->delete("/account/{$user->id}");

            $response->assertStatus(302);
            $response->assertRedirect("/login");

            $user->delete();
        }

        public function testUserCanAccessDestroyOwn() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->delete("/account/{$user->id}");

            $response->assertStatus(302);
            $response->assertRedirect("/");

        }

        public function testUserCannotAccessDestroyOwnOnSb() {
            $user = User::factory()->create(['role' => UserRole::USER]);
            $user2= User::factory()->create(['role' => UserRole::USER]);
            $response = $this->actingAs($user)->delete("/account/{$user2->id}");

            $response->assertStatus(403);

            $user->delete();
            $user2->delete();
        }



}
?>
