<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use App\Enums\UserRole;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProductCategory;

class ProductsCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotAccessIndex(): void
    {
        $response = $this->get(route('categories.index'));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessIndex(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route('categories.index'));
        $response->assertForbidden();
    }

    public function testAdminCanAccessIndex(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get(route('categories.index'));
        $response->assertOk();
    }

    public function testGuestCannotAccessCreate(): void
    {
        $response = $this->get(route('categories.create'));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessCreate(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route('categories.create'));
        $response->assertForbidden();
    }

    public function testAdminCanAccessCreate(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get(route('categories.create'));
        $response->assertOk();
    }

    public function testGuestCannotAccessStore(): void
    {
        $response = $this->post(route('categories.store'), [
            'name' => 'test',
        ]);
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessStore(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);

        $response = $this->actingAs($user)->post(route('categories.store'), [
            'name' => 'test',
        ]);
        $response->assertForbidden();
    }

    public function testAdminCanAccessStore(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);

        $response = $this->actingAs($user)->post(route('categories.store'), [
            'name' => 'test',
        ]);
        $response->assertRedirect(route('categories.index'));
    }

    public function testGuestCannotAccessEdit(): void
    {
        $productcategory = ProductCategory::factory()->create();
        $response = $this->get(route('categories.edit', $productcategory->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessEdit(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->get(route('categories.edit', $productcategory->id));
        $response->assertForbidden();
    }

    public function testAdminCanAccessEdit(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->get(route('categories.edit', $productcategory->id));
        $response->assertOk();
    }

    public function testGuestCannotAccessUpdate(): void
    {
        $productcategory = ProductCategory::factory()->create();
        $response = $this->put(route('categories.update', $productcategory->id), [
            'name' => 'test',
        ]);
        $category = ProductCategory::find($productcategory->id);
        $this->assertFalse($category->name == 'test');
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessUpdate(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->put(route('categories.update', $productcategory->id), [
            'name' => 'test',
        ]);
        $category = ProductCategory::find($productcategory->id);
        $this->assertFalse($category->name == "test");
        $response->assertForbidden();
    }

    public function testAdminCanAccessUpdate(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->put(route('categories.update', $productcategory->id), [
            'name' => 'test'
        ]);
        $category = ProductCategory::find($productcategory->id);
        $this->assertFalse($category->name == $productcategory->name);
        $response->assertRedirect(route('myfridges.indexOwn'));
    }

    public function testGuestCannotAccessDestroy(): void
    {
        $productcategory = ProductCategory::factory()->create();
        $response = $this->delete(route('categories.destroy', $productcategory->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessDestroy(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->delete(route('categories.destroy', $productcategory->id));
        $response->assertForbidden();
    }

    public function testAdminCanAccessDestroy(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $productcategory = ProductCategory::factory()->create();
        $response = $this->actingAs($user)->delete(route('categories.destroy', $productcategory->id));
        $response->assertRedirect(route('myfridges.indexOwn'));
        $this->assertNull(ProductCategory::find($productcategory->id));
    }
}
