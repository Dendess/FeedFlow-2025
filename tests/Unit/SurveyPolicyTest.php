<?php

namespace Tests\Unit;

use App\Models\Organization;
use App\Models\Survey;
use App\Models\User;
use App\Policies\SurveyPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SurveyPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected SurveyPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new SurveyPolicy();
    }

    public function test_any_authenticated_user_can_view_surveys_list(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_any_authenticated_user_can_create_survey(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->create($user));
    }

    public function test_organization_member_can_view_survey(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($member->id, ['role' => 'member']);

        $survey = Survey::create([
            'title' => 'Team Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $owner->id,
            'token' => Str::random(32),
        ]);

        $this->assertTrue($this->policy->view($member, $survey));
    }

    public function test_non_member_cannot_view_survey(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Private Org',
            'user_id' => $owner->id,
        ]);

        $survey = Survey::create([
            'title' => 'Private Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $owner->id,
            'token' => Str::random(32),
        ]);

        $this->assertFalse($this->policy->view($outsider, $survey));
    }

    public function test_admin_can_update_survey(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($admin->id, ['role' => 'admin']);

        $survey = Survey::create([
            'title' => 'Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $owner->id,
            'token' => Str::random(32),
        ]);

        $this->assertTrue($this->policy->update($admin, $survey));
    }

    public function test_regular_member_cannot_update_survey(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($member->id, ['role' => 'member']);

        $survey = Survey::create([
            'title' => 'Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $owner->id,
            'token' => Str::random(32),
        ]);

        $this->assertFalse($this->policy->update($member, $survey));
    }

    public function test_admin_can_delete_survey(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($admin->id, ['role' => 'admin']);

        $survey = Survey::create([
            'title' => 'Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $owner->id,
            'token' => Str::random(32),
        ]);

        $this->assertTrue($this->policy->delete($admin, $survey));
    }

    public function test_regular_member_cannot_delete_survey(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($member->id, ['role' => 'member']);

        $survey = Survey::create([
            'title' => 'Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $owner->id,
            'token' => Str::random(32),
        ]);

        $this->assertFalse($this->policy->delete($member, $survey));
    }

    public function test_organization_member_can_view_survey_results(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($member->id, ['role' => 'member']);

        $survey = Survey::create([
            'title' => 'Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $owner->id,
            'token' => Str::random(32),
        ]);

        $this->assertTrue($this->policy->viewResults($member, $survey));
    }

    public function test_non_member_cannot_view_survey_results(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Private Org',
            'user_id' => $owner->id,
        ]);

        $survey = Survey::create([
            'title' => 'Private Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $owner->id,
            'token' => Str::random(32),
        ]);

        $this->assertFalse($this->policy->viewResults($outsider, $survey));
    }
}
