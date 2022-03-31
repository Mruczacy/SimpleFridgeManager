<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProductCategory;

class ProductsCategoryRouteTest extends TestCase {

    use RefreshDatabase;
    public function testGuestCannotAccessIndex() {
        $response = $this->get("/products/categories");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessIndex() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get("/products/categories");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessIndex() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get("/products/categories");
        $response->assertStatus(200);
    }

    public function testGuestCannotAccessCreate() {
        $response = $this->get("/products/categories/create");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessCreate() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get("/products/categories/create");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessCreate() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get("/products/categories/create");
        $response->assertStatus(200);
    }

    public function testGuestCannotAccessStore() {
        $response = $this->post("/products/categories", [
            'name' => 'test',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessStore() {
        $user = User::factory()->create(['role' => UserRole::USER]);

        $response = $this->actingAs($user)->post("/products/categories", [
            'name' => 'test',
        ]);
        $response->assertStatus(403);
    }

    public function testAdminCanAccessStore() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($user)->post("/products/categories", [
            'name' => 'test',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/products/categories");
    }

    public function testGuestCannotAccessEdit() {
        $productcategory = ProductCategory::factory()->create();
        $response = $this->get("/products/categories/{$productcategory->id}/edit");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessEdit() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->get("/products/categories/{$productcategory->id}/edit");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessEdit() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->get("/products/categories/{$productcategory->id}/edit");
        $response->assertStatus(200);
    }

    public function testGuestCannotAccessUpdate() {
        $productcategory = ProductCategory::factory()->create();
        $response = $this->put("/products/categories/{$productcategory->id}", [
            'name' => 'test',
        ]);
        $category = ProductCategory::find($productcategory->id);
        $this->assertFalse($category->name == 'test');
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessUpdate() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->put("/products/categories/{$productcategory->id}", [
            'name' => 'test',
        ]);
        $category = ProductCategory::find($productcategory->id);
        $this->assertFalse($category->name == "test");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessUpdate() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->put("/products/categories/{$productcategory->id}", [
            'name' => 'test'
        ]);
        $category = ProductCategory::find($productcategory->id);
        $this->assertFalse($category->name == $productcategory->name);
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
    }

    public function testGuestCannotAccessDestroy() {
        $productcategory = ProductCategory::factory()->create();
        $response = $this->delete("/products/categories/{$productcategory->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessDestroy() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->delete("/products/categories/{$productcategory->id}");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessDestroy() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->delete("/products/categories/{$productcategory->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $this->assertNull(ProductCategory::find($productcategory->id));
    }
}
?>
