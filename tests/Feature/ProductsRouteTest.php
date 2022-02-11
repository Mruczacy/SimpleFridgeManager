<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Fridge;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Enums\UserRole;
use Carbon\Carbon;

class ProductsRouteTest extends TestCase {

    public function testGuestCannotAccessIndex() {
        $response = $this->get("/products");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessIndex() {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/products");

        $response->assertStatus(403);

        $user->delete();
    }

    public function testAdminCanAccessIndex() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($user)->get("/products");

        $response->assertStatus(200);

        $user->delete();
    }

    public function testGuestCannotAccessCreate() {
        $response = $this->get("/products/create");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessCreate() {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/products/create");

        $response->assertStatus(200);

        $user->delete();
    }

    public function testGuestCannotAccessStore()
    {
        $fridge = Fridge::factory()->create();
        $response = $this->post("/products", [
            'name' => 'test',
            'expiration_date' => Carbon::now()->addDays(2137),
            'fridge_id' => $fridge->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $fridge->delete();
    }

    public function testUserCannotAccessStoreProductOnSbsFridge()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $category = ProductCategory::factory()->create();
        $fridge->users()->attach($user2, ['is_owner' => 1]);
        $response = $this->actingAs($user)->post("/products", [
            'name' => 'test',
            'expiration_date' => Carbon::now()->addDays(2137),
            'fridge_id' => $fridge->id,
            'product_category_id' => $category->id,
        ]);

        $response->assertStatus(403);
        $fridge->users()->detach();
        $user2->delete();
        $user->delete();
        $fridge->delete();
        $category->delete();
    }

    public function testUserCanAccessStoreProductOnItsFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create();
        $category = ProductCategory::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_owner' => 0]);
        $response = $this->actingAs($user)->post("/products", [
            'name' => 'testa',
            'expiration_date' => Carbon::now()->addDays(2137),
            'fridge_id' => $fridge->id,
            'product_category_id' => $category->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $this->assertTrue($fridge->products->contains('name', 'testa'));
        $product= Product::find($fridge->products->where('name', 'testa')->first()->id);
        $product->delete();
        $user->fridges()->detach();
        $fridge->users()->detach();
        $user->delete();
        $fridge->delete();
        $category->delete();
    }
}
?>
