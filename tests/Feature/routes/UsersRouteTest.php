<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Enums\UserRole;
use App\Models\User;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotAccessIndex(): void
    {
        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessIndex(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertForbidden();
    }

    public function testAdminCanAccessIndex(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertOk();
    }

    public function testGuestCannotAccessShow(): void
    {
        $response = $this->get(route('users.showMyAccount'));
        $response->assertRedirect(route('login'));
    }

    public function testUserCanAccessShow(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route('users.showMyAccount'));
        $response->assertOk();
    }

    public function testGuestCannotAccessEdit(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->get(route('users.edit', $user->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessEdit(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route('users.edit', $user2->id));
        $response->assertForbidden();
    }

    public function testAdminCanAccessEdit(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route('users.edit', $user2->id));
        $response->assertOk();
    }

    public function testGuestCannotAccessEditOwn(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->get(route('users.editOwn', $user->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCanAccessEditOwn(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route('users.editOwn', $user->id));
        $response->assertOk();
    }

    public function testUserCannotAccessEditOwnOnSb(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->get(route('users.editOwn', $user2->id));
        $response->assertForbidden();
    }

    public function testGuestCannotAccessUpdate(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->put(route('users.update', $user->id), [
            'name' => 'test',
            'email' => $user->email
        ]);
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessUpdate(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->put(route('users.update', $user2->id), [
            'name' => 'test',
            'email' => $user2->email
        ]);
        $response->assertForbidden();
    }

    public function testAdminCanAccessUpdate(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->put(route('users.update', $user2->id), [
            'name' => 'test',
            'email' => $user2->email,
            'role' => UserRole::ADMIN,
        ]);
        $response->assertRedirect(route('users.index'));
        $test = User::find($user2->id);
        $this->assertTrue($test->role == UserRole::ADMIN);
    }

    public function testGuestCannotAccessUpdateOwn(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->put(route('users.updateOwn', $user->id), [
            'name' => 'test',
            'email' => $user->email
        ]);
        $response->assertRedirect(route('login'));
    }

    public function testUserCanAccessUpdateOwn(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->put(route('users.updateOwn', $user->id), [
            'name' => 'test',
            'email' => $user->email
        ]);
        $response->assertRedirect(route('users.showMyAccount'));
    }

    public function testUserCannotAccessUpdateOwnOnSb(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->put(route('users.updateOwn', $user2->id), [
            'name' => 'test',
            'email' => $user2->email
        ]);
        $response->assertForbidden();
    }

    public function testGuestCannotAccessDestroy(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->delete(route('users.destroy', $user->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCannotAccessDestroy(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->delete(route('users.destroy', $user2->id));
        $response->assertForbidden();
    }

    public function testAdminCanAccessDestroy(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->delete(route('users.destroy', $user2->id));
        $response->assertRedirect(route('users.index'));
    }

    public function testGuestCannotAccessDestroyOwn(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->delete(route('users.destroyOwn', $user->id));
        $response->assertRedirect(route('login'));
    }

    public function testUserCanAccessDestroyOwn(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->delete(route('users.destroyOwn', $user->id));
        $response->assertRedirect(route('welcome'));
    }

    public function testUserCannotAccessDestroyOwnOnSb(): void
    {
        $user = User::factory()->create(['role' => UserRole::USER]);
        $user2 = User::factory()->create(['role' => UserRole::USER]);
        $response = $this->actingAs($user)->delete(route('users.destroyOwn', $user2->id));
        $response->assertForbidden();
    }
}
