<?php

namespace Tests\Unit;

use App\Models\Organization;
use App\Models\User;
use App\Policies\OrganizationPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected OrganizationPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new OrganizationPolicy();
    }

    public function test_any_authenticated_user_can_view_organizations_list(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_any_authenticated_user_can_create_organization(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->create($user));
    }

    public function test_owner_can_view_their_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'My Org',
            'user_id' => $user->id,
        ]);

        $this->assertTrue($this->policy->view($user, $organization));
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

        $this->assertTrue($this->policy->view($admin, $organization));
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

        $this->assertTrue($this->policy->view($member, $organization));
    }

    public function test_non_member_cannot_view_organization(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Private Org',
            'user_id' => $owner->id,
        ]);

        $this->assertFalse($this->policy->view($outsider, $organization));
    }

    public function test_owner_can_update_their_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'My Org',
            'user_id' => $user->id,
        ]);

        $this->assertTrue($this->policy->update($user, $organization));
    }

    public function test_admin_member_can_update_organization(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Shared Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($admin->id, ['role' => 'admin']);

        $this->assertTrue($this->policy->update($admin, $organization));
    }

    public function test_regular_member_cannot_update_organization(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Team Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($member->id, ['role' => 'member']);

        $this->assertFalse($this->policy->update($member, $organization));
    }

    public function test_owner_can_delete_their_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'My Org',
            'user_id' => $user->id,
        ]);

        $this->assertTrue($this->policy->delete($user, $organization));
    }

    public function test_admin_member_cannot_delete_organization(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Protected Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($admin->id, ['role' => 'admin']);

        $this->assertFalse($this->policy->delete($admin, $organization));
    }

    public function test_regular_member_cannot_delete_organization(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Protected Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($member->id, ['role' => 'member']);

        $this->assertFalse($this->policy->delete($member, $organization));
    }
}
