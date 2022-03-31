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

class MyProductsRouteTest extends TestCase {

    use RefreshDatabase;
    public function testGuestCannotAccessEditOwn() {
        $product = Product::factory()->create();
        $response = $this->get("/myproducts/{$product->id}/edit");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $product->delete();
    }

    public function testUserCanAccessEditOwn() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create();
        $fridge->users()->attach($user, ['is_manager' => false]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);

        $response = $this->actingAs($user)->get("/myproducts/{$product->id}/edit");
        $product->delete();
        $response->assertStatus(200);
        $fridge->users()->detach();
        $fridge->delete();

        $user->delete();
    }

    public function testUserCannotAccessEditOwnOnSbs() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create();
        $fridge->users()->attach($user2, ['is_manager' => false]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
        ]);

        $response = $this->actingAs($user)->get("/myproducts/{$product->id}/edit");
        $product->delete();
        $response->assertStatus(403);
        $fridge->users()->detach();
        $fridge->delete();

        $user->delete();
        $user2->delete();
    }

    public function testGuestCannotAccessUpdateOwn() {
        $category = ProductCategory::factory()->create();
        $category2 = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'name' => 'test2',
            'product_category_id' => $category->id,
        ]);
        $response = $this->put("/myproducts/{$product->id}", [
            'name' => 'test',
            'product_category_id' => $category2->id,
        ]);
        $product2 = Product::find($product->id);
        $this->assertTrue($product2->category_id == $product->category_id);
        $this->assertTrue($product2->name == $product->name);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $product->delete();
        $category->delete();
        $category2->delete();
    }

    public function testUserCanAccessUpdateOwn() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create();
        $fridge->users()->attach($user, ['is_manager' => false]);
        $category = ProductCategory::factory()->create();
        $category2 = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'name' => 'test2',
            'product_category_id' => $category->id,
            'fridge_id' => $fridge->id,
        ]);
        $response = $this->actingAs($user)->put("/myproducts/{$product->id}", [
            'name' => 'test',
            'product_category_id' => $category2->id,
            'fridge_id' => $fridge->id,
            'expiration_date' => Carbon::now()->addDays(2137),
        ]);
        $product2 = Product::find($product->id);
        $this->assertTrue($product2->product_category_id != $product->product_category_id);
        $this->assertTrue($product2->name == 'test');
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $product->delete();
        $fridge->users()->detach();
        $fridge->delete();
        $user->delete();
        $category->delete();
        $category2->delete();
    }

    public function testUserCannotAccessUpdateOwnOnSbs() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create();
        $fridge->users()->attach($user2, ['is_manager' => false]);
        $category = ProductCategory::factory()->create();
        $category2 = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'name' => 'test2',
            'product_category_id' => $category->id,
            'fridge_id' => $fridge->id,
        ]);
        $response = $this->actingAs($user)->put("/myproducts/{$product->id}", [
            'name' => 'test',
            'product_category_id' => $category2->id,
            'fridge_id' => $fridge->id,
            'expiration_date' => Carbon::now()->addDays(2137),
        ]);
        $product2 = Product::find($product->id);
        $this->assertTrue($product2->product_category_id == $product->product_category_id);
        $this->assertTrue($product2->name == $product->name);
        $response->assertStatus(403);
        $product->delete();
        $fridge->users()->detach();
        $fridge->delete();
        $user->delete();
        $user2->delete();
        $category->delete();
        $category2->delete();
    }

    public function testGuestCannotAccessDestroyOwn() {
        $product = Product::factory()->create();
        $response = $this->delete("/myproducts/{$product->id}");

        $response->assertStatus(302);
        $response->assertRedirect("/login");
        $product->delete();
    }

    public function testUserCanAccessDestroyOwn() {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create();
        $fridge->users()->attach($user, ['is_manager' => true]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);

        $response = $this->actingAs($user)->delete("/myproducts/{$product->id}");

        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");

        $this->assertNull(Product::find($product->id));
        $fridge->users()->detach();
        $fridge->delete();
        $user->delete();
    }

    public function testUserCannotAccessDestroyOwnOnSbs() {
        $user = User::factory()->create(['role' => UserRole::USER]);

        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $fridge = Fridge::factory()->create();
        $fridge2= Fridge::factory()->create();
        $fridge->users()->attach($user, ['is_manager' => true]);
        $fridge2->users()->attach($user2, ['is_manager' => true]);
        $product = Product::factory()->create(['fridge_id' => $fridge2->id]);

        $response = $this->actingAs($user)->delete("/myproducts/{$product->id}");
        $response->assertStatus(403);

        $product->delete();
        $fridge->users()->detach();
        $fridge2->users()->detach();
        $fridge->delete();
        $fridge2->delete();
        $user->delete();
        $user2->delete();
    }
}

?>
