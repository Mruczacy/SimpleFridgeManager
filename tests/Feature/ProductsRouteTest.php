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
        $fridge= Fridge::factory()->create();
        $response = $this->get("/products/create/{$fridge->id}");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $fridge->delete();
    }

    public function testUserCanAccessCreateInUsedFridge() {
        $user = User::factory()->create();
        $fridge= Fridge::factory()->create();
        $user->fridges()->attach($fridge, ['is_owner' => false]);
        $response = $this->actingAs($user)->get("/products/create/{$fridge->id}");
        $user->fridges()->detach($fridge);
        $fridge->delete();
        $user->delete();
        $response->assertStatus(200);

        $user->delete();
    }
    public function testUserCanotAccessCreateOnSbsFridge() {
        $user = User::factory()->create();
        $fridge= Fridge::factory()->create();
        $response = $this->actingAs($user)->get("/products/create/{$fridge->id}");
        $response->assertStatus(403);
        $fridge->delete();
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
        $response->assertRedirect("/myfridges/{$fridge->id}");
        $this->assertTrue($fridge->products->contains('name', 'testa'));
        $this->assertNotNull(Product::find($fridge->products->where('name', 'testa')->first()->id));
        Product::find($fridge->products->where('name', 'testa')->first()->id)->delete();
        $user->fridges()->detach();
        $fridge->users()->detach();
        $user->delete();
        $fridge->delete();
        $category->delete();
    }

    public function testGuestCannotAccessEdit()
    {
        $product = Product::factory()->create();
        $response = $this->get("/products/{$product->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $product->delete();
    }

    public function testUserCannotAccessEdit()
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->get("/products/{$product->id}/edit");

        $response->assertStatus(403);
        $product->delete();
        $user->delete();
    }

    public function testAdminCanAccessEdit()
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->get("/products/{$product->id}/edit");

        $response->assertStatus(200);
        $product->delete();
        $user->delete();
    }

    public function testGuestCannotAccessUpdate()
    {
        $product = Product::factory()->create();
        $response = $this->put("/products/{$product->id}", [
            'name' => 'test',
            'expiration_date' => Carbon::now()->addDays(2137),
            'fridge_id' => $product->fridge_id,
            'product_category_id' => $product->product_category_id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $product->delete();
    }

    public function testUserCannotAccessUpdate()
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->put("/products/{$product->id}", [
            'name' => 'test',
            'expiration_date' => Carbon::now()->addDays(2137),
            'fridge_id' => $product->fridge_id,
            'product_category_id' => $product->product_category_id,
        ]);

        $response->assertStatus(403);
        $product->delete();
        $user->delete();
    }

    public function testAdminCanAccessUpdate()
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->put("/products/{$product->id}", [
            'name' => 'test',
            'expiration_date' => Carbon::now()->addDays(2137),
            'fridge_id' => $product->fridge_id,
            'product_category_id' => $product->product_category_id,
        ]);
        $product2 = Product::find($product->id);
        $this->assertFalse($product2->name == $product->name);
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $product->delete();
        $user->delete();
    }

    public function testGuestCannotAccessMoveProductsBetweenFridges() {
        $fridge = Fridge::factory()->create();
        $fridge2 = Fridge::factory()->create();
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);

        $response = $this->put("/products/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $product->delete();
        $fridge->delete();
        $fridge2->delete();
    }

    public function testUserCannotAccessMoveProductsBetweenFridges() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create();
        $fridge2 = Fridge::factory()->create();
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);

        $response = $this->actingAs($user)->put("/products/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $response->assertStatus(403);
        $product->delete();
        $fridge->delete();
        $fridge2->delete();
        $user->delete();
    }

    public function testAdminCanAccessMoveProductsBetweenFridges() {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $fridge = Fridge::factory()->create();
        $fridge2 = Fridge::factory()->create();
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
        $product->delete();
        $fridge->delete();
        $fridge2->delete();
        $user->delete();
    }

    public function testGuestCannotMoveProductsBetweenFridgesOwn(){
        $fridge = Fridge::factory()->create();
        $fridge2 = Fridge::factory()->create();
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);

        $response = $this->put("/myproducts/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $product->delete();
        $fridge->delete();
        $fridge2->delete();
    }

    public function testUserCanMoveProductsBetweenItsOwnFridges() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create();
        $fridge2 = Fridge::factory()->create();
        $fridge->users()->attach($user, ['is_owner' => 0]);
        $fridge2->users()->attach($user, ['is_owner' => 0]);
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
        $fridge->users()->detach();
        $fridge2->users()->detach();
        $product->delete();
        $fridge->delete();
        $fridge2->delete();
        $user->delete();
    }

    public function testUserCannotMoveProductsBetweenFridgesOfOtherUsers() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create();
        $fridge2 = Fridge::factory()->create();
        $fridge->users()->attach($user2, ['is_owner' => 0]);
        $fridge2->users()->attach($user2, ['is_owner' => 0]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);

        $response = $this->actingAs($user)->put("/myproducts/move/{$product->id}", [
            'fridge_id' => $fridge2->id,
        ]);
        $this->assertTrue($product->fridge_id == $fridge->id);
        $response->assertStatus(403);
        $product->delete();
        $fridge->users()->detach();
        $fridge2->users()->detach();
        $fridge->delete();
        $fridge2->delete();
        $user->delete();
        $user2->delete();
    }

    public function testGuestCannotAccessDestroy()
    {
        $product = Product::factory()->create();
        $response = $this->delete("/products/{$product->id}");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $product->delete();
    }

    public function testUserCannotAccessDestroy()
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->delete("/products/{$product->id}");

        $response->assertStatus(403);
        $product->delete();
        $user->delete();
    }

    public function testAdminCanAccessDestroy()
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->delete("/products/{$product->id}");
        $this->assertNull(Product::find($product->id));
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $user->delete();
    }

}
?>
