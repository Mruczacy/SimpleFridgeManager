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
    public function testGuestCannotAccessIndex(): void
    {
        $response = $this->get("/products/categories");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessIndex(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get("/products/categories");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessIndex(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get("/products/categories");
        $response->assertStatus(200);
    }

    public function testGuestCannotAccessCreate(): void
    {
        $response = $this->get("/products/categories/create");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessCreate(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get("/products/categories/create");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessCreate(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get("/products/categories/create");
        $response->assertStatus(200);
    }

    public function testGuestCannotAccessStore(): void
    {
        $response = $this->post("/products/categories", [
            'name' => 'test',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessStore(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);

        $response = $this->actingAs($user)->post("/products/categories", [
            'name' => 'test',
        ]);
        $response->assertStatus(403);
    }

    public function testAdminCanAccessStore(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($user)->post("/products/categories", [
            'name' => 'test',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/products/categories");
    }

    public function testGuestCannotAccessEdit(): void
    {
        $productcategory = ProductCategory::factory()->create();
        $response = $this->get("/products/categories/{$productcategory->id}/edit");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessEdit(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->get("/products/categories/{$productcategory->id}/edit");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessEdit(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->get("/products/categories/{$productcategory->id}/edit");
        $response->assertStatus(200);
    }

    public function testGuestCannotAccessUpdate(): void
    {
        $productcategory = ProductCategory::factory()->create();
        $response = $this->put("/products/categories/{$productcategory->id}", [
            'name' => 'test',
        ]);
        $category = ProductCategory::find($productcategory->id);
        $this->assertFalse($category->name == 'test');
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessUpdate(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->put("/products/categories/{$productcategory->id}", [
            'name' => 'test',
        ]);
        $category = ProductCategory::find($productcategory->id);
        $this->assertFalse($category->name == "test");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessUpdate(): void
    {
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

    public function testGuestCannotAccessDestroy(): void
    {
        $productcategory = ProductCategory::factory()->create();
        $response = $this->delete("/products/categories/{$productcategory->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessDestroy(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->delete("/products/categories/{$productcategory->id}");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessDestroy(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->delete("/products/categories/{$productcategory->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $this->assertNull(ProductCategory::find($productcategory->id));
    }
}
?>
