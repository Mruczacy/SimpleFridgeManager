<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Fridge;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagementsRouteTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotGetAManageForm(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $response = $this->get(route('manage.showAManageForm', $fridge->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotGetAManageFormOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge->users()->attach($user2->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->get(route('manage.showAManageForm', $fridge->id));
        $response->assertForbidden();
    }

    public function testUserCanGetAManageFormOnOwnFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $fridge->users()->attach($user->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->get(route('manage.showAManageForm', $fridge->id));
        $response->assertOk();
    }

    public function testGuestCannotGetAMoveForm(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);
        $response = $this->get(route('products.moveform', [$product->id, $fridge->id]));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotGetAMoveForm(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge->users()->attach($user2->id, ['is_manager' => 1]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);
        $response = $this->actingAs($user)->get(route('products.moveform', [$product->id, $fridge->id]));
        $response->assertForbidden();
    }

    public function testAdminCanGetAMoveForm(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge->users()->attach($user2->id, ['is_manager' => 1]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);
        $response = $this->actingAs($user)->get(route("products.moveform", [$product->id, $fridge->id]));
        $response->assertOk();
    }

    public function testGuestCannotGetAMoveFormOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge->users()->attach($user2->id, ['is_manager' => 1]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);
        $response = $this->get(route('myproducts.moveform', [$product->id, $fridge->id]));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotGetAMoveFormOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge->users()->attach($user2->id, ['is_manager' => 1]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);
        $response = $this->actingAs($user)->get(route('myproducts.moveform', [$product->id, $fridge->id]));
        $response->assertForbidden();
    }

    public function testUserCanGetAMoveFormOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge->users()->attach($user2->id, ['is_manager' => 1]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);
        $response = $this->actingAs($user2)->get(route('myproducts.moveform', [$product->id, $fridge->id]));
        $response->assertOk();
    }

    public function testGuestCannotAttachFridgeToSb(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->post(route("manage.attach", $fridge->id), [
            'is_manager' => 1,
            'user_id' => $user->id,
        ]);
        $response->assertRedirect(route('login'));
    }

    public function testNoOwnerUserCannotAttachFridgeToSb(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user3->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->post(route("manage.attach", $fridge->id), [
            'is_manager' => 1,
            'user_id' => $user2->id,
        ]);
        $response->assertForbidden();
    }

    public function testOwnerUserCanAttachFridgeToSb(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->post(route("manage.attach", $fridge->id), [
            'is_manager' => 1,
            'user_id' => $user2->id,
        ]);
        $response->assertRedirect(route('myfridges.indexOwn'));
        $this->assertTrue($user2->fridges->contains($fridge->id));
    }

    public function testGuestCannotDetachFridgeFromSb(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->delete(route('manage.detach', [$fridge->id, $user->id]));
        $response->assertRedirect(route('login'));
    }

    public function testNoOwnerUserCannotDetachFridgeFromSb(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->delete(route('manage.detach', [$fridge->id, $user->id]));
        $user->fridges()->detach();
        $response->assertForbidden();
    }

    public function testOwnerUserCanDetachFridgeFromSb(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $user2->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->delete(route('manage.detach', [$fridge->id, $user2->id]));
        $response->assertRedirect(route('myfridges.indexOwn'));
        $this->assertFalse($user2->fridges->contains($fridge->id));
    }

    public function testGuestCannotResignFromSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->post(route('manage.resign', $fridge->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotResignFromSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user2->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->post(route('manage.resign', $fridge->id));
        $response->assertForbidden();
    }

    public function testUserCanResignFromAccessibleFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->post(route('manage.resign', $fridge->id));
        $response->assertRedirect(route('myfridges.indexOwn'));
        $this->assertFalse($fridge->users->contains($user->id));
    }

    public function testOwnerCannotResignFromAccessibleFridge(): void
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->post(route('manage.resign', $fridge->id));
        $response->assertForbidden();
    }

    public function testGuestCannotTransferOwnerShip(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->put(route('manage.transferOwnership', $fridge->id), [
            'owner_id' => $user->id,
        ]);
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotTransferOwnershipOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->put(route('manage.transferOwnership', $fridge->id), [
            'owner_id' => $user->id,
        ]);
        $response->assertForbidden();
    }

    public function testOwnerCanTransferOwnership(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->put(route('manage.transferOwnership', $fridge->id), [
            'owner_id' => $user2->id,
        ]);
        $response->assertRedirect(route('myfridges.indexOwn'));
        $fridge = Fridge::find($fridge->id);
        $this->assertTrue($fridge->owner_id == $user2->id);
    }

    public function testGuestCannotUpdateUserRank(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->put(route('manage.updateUserRank', $fridge->id), [
            'user_id' => $user->id,
            'is_manager' => 1,
        ]);
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotUpdateUserRankOnSbsFridge(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->put(route('manage.updateUserRank', $fridge->id), [
            'user_id' => $user->id,
            'is_manager' => 1,
        ]);
        $response->assertForbidden();
    }

    public function testOwnerCanUpdateUserRank(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user2->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->put(route('manage.updateUserRank', $fridge->id), [
            'user_id' => $user2->id,
            'is_manager' => 1,
        ]);
        $response->assertRedirect(route('myfridges.indexOwn'));
        $this->assertTrue($fridge->managers->contains($user2->id));
    }
}
