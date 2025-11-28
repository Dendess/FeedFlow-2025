<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_organizations_list(): void
    {
        $user = User::factory()->create();
        
        Organization::create([
            'name' => 'My Organization',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('organizations.index'));

        $response->assertStatus(200);
        $response->assertSee('My Organization');
    }

    public function test_user_can_create_organization(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('organizations.store'), [
            'name' => 'New Organization',
        ]);

        $response->assertRedirect(route('organizations.index'));
        $this->assertDatabaseHas('organizations', [
            'name' => 'New Organization',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_view_own_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('organizations.show', $organization));

        $response->assertStatus(200);
        $response->assertSee('Test Org');
    }

    public function test_user_can_update_own_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Old Name',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->put(route('organizations.update', $organization), [
            'name' => 'Updated Name',
        ]);

        $response->assertRedirect(route('organizations.show', $organization));
        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_user_can_delete_own_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'To Delete',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('organizations.destroy', $organization));

        $response->assertRedirect(route('organizations.index'));
        $this->assertDatabaseMissing('organizations', [
            'id' => $organization->id,
        ]);
    }

    public function test_user_cannot_view_other_users_organization(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Private Org',
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($otherUser)->get(route('organizations.show', $organization));

        $response->assertStatus(403);
    }

    public function test_user_cannot_update_other_users_organization(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Private Org',
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($otherUser)->put(route('organizations.update', $organization), [
            'name' => 'Hacked Name',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'name' => 'Private Org',
        ]);
    }

    public function test_admin_member_can_view_organization(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Shared Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($admin->id, ['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('organizations.show', $organization));

        $response->assertStatus(200);
        $response->assertSee('Shared Org');
    }

    public function test_regular_member_can_view_organization(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Team Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($member->id, ['role' => 'member']);

        $response = $this->actingAs($member)->get(route('organizations.show', $organization));

        $response->assertStatus(200);
        $response->assertSee('Team Org');
    }

    public function test_organization_name_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('organizations.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_organization_name_must_be_unique(): void
    {
        $user = User::factory()->create();
        
        Organization::create([
            'name' => 'Existing Org',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('organizations.store'), [
            'name' => 'Existing Org',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
