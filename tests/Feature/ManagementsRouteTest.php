<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Fridge;

class ManagementsRouteTest extends TestCase

{

    use RefreshDatabase;
    public function testGuestCannotGetAManageForm()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $response = $this->get("/manage/form/{$fridge->id}");
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUserCannotGetAManageFormOnSbsFridge()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $fridge->users()->attach($user2->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->get("/manage/form/{$fridge->id}");
        $response->assertStatus(403);
    }

    public function testUserCanGetAManageFormOnOwnFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $fridge->users()->attach($user->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->get("/manage/form/{$fridge->id}");
        $response->assertStatus(200);
    }

    public function testGuestCannotAttachFridgeToSb()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->post("/manage/attach/{$fridge->id}", [
            'is_manager' => 1,
            'user_id' => $user->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testNoOwnerUserCannotAttachFridgeToSb()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user3->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->post("/manage/attach/{$fridge->id}", [
            'is_manager' => 1,
            'user_id' => $user2->id,
        ]);
        $response->assertStatus(403);
    }

    public function testOwnerUserCanAttachFridgeToSb()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->post("/manage/attach/{$fridge->id}", [
            'is_manager' => 1,
            'user_id' => $user2->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $this->assertTrue($user2->fridges->contains($fridge->id));
    }

    public function testGuestCannotDetachFridgeFromSb()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $response = $this->delete("/manage/detach/{$fridge->id}/{$user->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testNoOwnerUserCannotDetachFridgeFromSb()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->delete("/manage/detach/{$fridge->id}/{$user->id}");
        $user->fridges()->detach();
        $response->assertStatus(403);
    }

    public function testOwnerUserCanDetachFridgeFromSb()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $user2->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->delete("/manage/detach/{$fridge->id}/{$user2->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $this->assertFalse($user2->fridges->contains($fridge->id));
    }

    public function testGuestCannotResignFromSbsFridge()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->post("/manage/resign/{$fridge->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotResignFromSbsFridge()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user2->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->post("/manage/resign/{$fridge->id}");
        $response->assertStatus(403);
    }

    public function testUserCanResignFromAccessibleFridge()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->post("/manage/resign/{$fridge->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $this->assertFalse($fridge->users->contains($user->id));
    }

    public function testOwnerCannotResignFromAccessibleFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->post("/manage/resign/{$fridge->id}");
        $response->assertStatus(403);
    }

    public function testGuestCannotTransferOwnerShip()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->put("/manage/transfer/{$fridge->id}", [
            'owner_id' => $user->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotTransferOwnershipOnSbsFridge()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->put("/manage/transfer/{$fridge->id}", [
            'owner_id' => $user->id,
        ]);
        $response->assertStatus(403);
    }

    public function testOwnerCanTransferOwnership()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 1]);
        $response = $this->actingAs($user)->put("/manage/transfer/{$fridge->id}", [
            'owner_id' => $user2->id,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $fridge = Fridge::find($fridge->id);
        $this->assertTrue($fridge->owner_id == $user2->id);
    }

    public function testGuestCannotUpdateUserRank()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->put("/manage/updaterank/{$fridge->id}", [
            'user_id' => $user->id,
            'is_manager' => 1,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function testUserCannotUpdateUserRankOnSbsFridge()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user2->id]);
        $user->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->put("/manage/updaterank/{$fridge->id}", [
            'user_id' => $user->id,
            'is_manager' => 1,
        ]);
        $response->assertStatus(403);
    }

    public function testOwnerCanUpdateUserRank()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $user2->fridges()->attach($fridge->id, ['is_manager' => 0]);
        $response = $this->actingAs($user)->put("/manage/updaterank/{$fridge->id}", [
            'user_id' => $user2->id,
            'is_manager' => 1,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/myfridges");
        $this->assertTrue($fridge->managers->contains($user2->id));
    }
}

?>
