<?php

declare(strict_types=1);

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

    use RefreshDatabase;
    public function testGuestCannotAccessIndex(): void
    {
        $response = $this->get("/products");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessIndex(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/products");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessIndex(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get("/products");
        $response->assertStatus(200);
    }

    public function testGuestCannotAccessCreate(): void
    {
        $user= User::factory()->create();
        $fridge= Fridge::factory()->create(['owner_id' => $user->id]);
        $response = $this->get("/products/create/{$fridge->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanAccessCreateInUsedFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge, ['is_manager' => false]);
        $response = $this->actingAs($user)->get("/products/create/{$fridge->id}");
        $user->fridges()->detach($fridge);
        $response->assertStatus(200);
    }
    public function testUserCanotAccessCreateOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge= Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->actingAs($user)->get("/products/create/{$fridge->id}");
        $response->assertStatus(403);
    }

    public function testGuestCannotAccessStore(): void
    {
        $user= User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $response = $this->post("/products", [
            'name' => 'test',
            'expiration_date' => now()->addDays(2137),
            'fridge_id' => $fridge->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");

    }

    public function testUserCannotAccessStoreProductOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $category = ProductCategory::factory()->create();
        $fridge->users()->attach($user2, ['is_manager' => 1]);
        $response = $this->actingAs($user)->post("/products", [
            'name' => 'test',
            'expiration_date' => now()->addDays(2137),
            'fridge_id' => $fridge->id,
            'product_category_id' => $category->id,
        ]);

        $response->assertStatus(403);
        $fridge->users()->detach();
    }

    public function testUserCanAccessStoreProductOnItsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $category = ProductCategory::factory()->create();
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->post("/products", [
            'name' => 'testa',
            'expiration_date' => now()->addDays(2137),
            'fridge_id' => $fridge->id,
            'product_category_id' => $category->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges/{$fridge->id}");
        $this->assertTrue($fridge->products->contains('name', 'testa'));
        $this->assertNotNull(Product::find($fridge->products->where('name', 'testa')->first()->id));
        Product::find($fridge->products->where('name', 'testa')->first()->id)->delete();

    }

    public function testGuestCannotAccessEdit(): void
    {
        $product = Product::factory()->create();
        $response = $this->get("/products/{$product->id}/edit");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessEdit(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->get("/products/{$product->id}/edit");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessEdit(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->get("/products/{$product->id}/edit");
        $response->assertStatus(200);
    }

    public function testGuestCannotAccessUpdate(): void
    {
        $product = Product::factory()->create();
        $response = $this->put("/products/{$product->id}", [
            'name' => 'test',
            'expiration_date' => now()->addDays(2137),
            'fridge_id' => $product->fridge_id,
            'product_category_id' => $product->product_category_id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessUpdate(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->put("/products/{$product->id}", [
            'name' => 'test',
            'expiration_date' => now()->addDays(2137),
            'fridge_id' => $product->fridge_id,
            'product_category_id' => $product->product_category_id,
        ]);
        $response->assertStatus(403);
    }

    public function testAdminCanAccessUpdate(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->put("/products/{$product->id}", [
            'name' => 'test',
            'expiration_date' => now()->addDays(2137),
            'fridge_id' => $product->fridge_id,
            'product_category_id' => $product->product_category_id,
        ]);
        $product2 = Product::find($product->id);
        $this->assertFalse($product2->name == $product->name);
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
    }

    public function testGuestCannotAccessMoveProductsBetweenFridges(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge2 = Fridge::factory()->create(['owner_id' => $user->id]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);
        $response = $this->put("/products/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessMoveProductsBetweenFridges(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge2 = Fridge::factory()->create(['owner_id' => $user3->id]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);
        $response = $this->actingAs($user)->put("/products/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $response->assertStatus(403);
    }

    public function testAdminCanAccessMoveProductsBetweenFridges(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge2 = Fridge::factory()->create(['owner_id' => $user3->id]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);
        $response = $this->actingAs($user)->put("/products/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $product2 = Product::find($product->id);
        $this->assertFalse($product2->fridge_id == $product->fridge_id);
        $response->assertStatus(302);
        $response->assertRedirect("/fridges");
    }

    public function testGuestCannotMoveProductsBetweenFridgesOwn(): void
    {
        $user= User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $fridge2 = Fridge::factory()->create(['owner_id' => $user2->id]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);
        $response = $this->put("/myproducts/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCanMoveProductsBetweenItsOwnFridges(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge2 = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge->users()->attach($user, ['is_manager' => 0]);
        $fridge2->users()->attach($user, ['is_manager' => 0]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);
        $response = $this->actingAs($user)->put("/myproducts/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $product2 = Product::find($product->id);
        $this->assertFalse($product2->fridge_id == $product->fridge_id);
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
    }

    public function testUserCannotMoveProductsBetweenFridgesOfOtherUsers(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $user3 = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create(['owner_id' => $user3->id]);
        $fridge2 = Fridge::factory()->create(['owner_id' => $user3->id]);
        $fridge->users()->attach($user2, ['is_manager' => 0]);
        $fridge2->users()->attach($user2, ['is_manager' => 0]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);
        $response = $this->actingAs($user)->put("/myproducts/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $this->assertTrue($product->fridge_id == $fridge->id);
        $response->assertStatus(403);
    }

    public function testGuestCannotAccessDestroy(): void
    {
        $product = Product::factory()->create();
        $response = $this->delete("/products/{$product->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotAccessDestroy(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->delete("/products/{$product->id}");
        $response->assertStatus(403);
    }

    public function testAdminCanAccessDestroy(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->delete("/products/{$product->id}");
        $this->assertNull(Product::find($product->id));
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
    }
}
