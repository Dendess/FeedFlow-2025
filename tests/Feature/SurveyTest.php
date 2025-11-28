<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_surveys_list(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'admin']);

        $survey = Survey::create([
            'title' => 'Test Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => Str::random(32),
        ]);

        $response = $this->actingAs($user)->get(route('surveys.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Survey');
    }

    public function test_admin_can_create_survey(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'admin']);
        $user->update(['current_organization_id' => $organization->id]);

        $response = $this->actingAs($user)->post(route('surveys.store'), [
            'organization_id' => $organization->id,
            'title' => 'New Survey',
            'description' => 'Survey description',
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addWeek()->format('Y-m-d'),
            'is_anonymous' => false,
        ]);

        $response->assertRedirect(route('surveys.index'));
        $this->assertDatabaseHas('surveys', [
            'title' => 'New Survey',
            'organization_id' => $organization->id,
        ]);
    }

    public function test_member_cannot_create_survey(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($member->id, ['role' => 'member']);

        $response = $this->actingAs($member)->post(route('surveys.store'), [
            'organization_id' => $organization->id,
            'title' => 'Unauthorized Survey',
            'description' => 'Description',
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addWeek()->format('Y-m-d'),
            'is_anonymous' => false,
        ]);

        $response->assertStatus(403);
    }

    public function test_survey_generates_unique_token(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $survey1 = Survey::create([
            'title' => 'Survey 1',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => Str::random(32),
        ]);

        $survey2 = Survey::create([
            'title' => 'Survey 2',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => Str::random(32),
        ]);

        $this->assertNotEquals($survey1->token, $survey2->token);
        $this->assertEquals(32, strlen($survey1->token));
        $this->assertEquals(32, strlen($survey2->token));
    }

    public function test_admin_can_view_survey(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'admin']);

        $survey = Survey::create([
            'title' => 'Test Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => Str::random(32),
        ]);

        $response = $this->actingAs($user)->get(route('surveys.show', $survey));

        $response->assertStatus(200);
        $response->assertSee('Test Survey');
    }

    public function test_member_can_view_survey(): void
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

        $response = $this->actingAs($member)->get(route('surveys.show', $survey));

        $response->assertStatus(200);
        $response->assertSee('Team Survey');
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

        $response = $this->actingAs($outsider)->get(route('surveys.show', $survey));

        $response->assertStatus(403);
    }

    public function test_admin_can_update_survey(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'admin']);

        $survey = Survey::create([
            'title' => 'Old Title',
            'description' => 'Old Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => Str::random(32),
        ]);

        $response = $this->actingAs($user)->put(route('surveys.update', $survey), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addWeek()->format('Y-m-d'),
            'is_anonymous' => true,
        ]);

        $response->assertRedirect(route('surveys.show', $survey));
        $this->assertDatabaseHas('surveys', [
            'id' => $survey->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'is_anonymous' => true,
        ]);
    }

    public function test_member_cannot_update_survey(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $owner->id,
        ]);

        $organization->users()->attach($member->id, ['role' => 'member']);

        $survey = Survey::create([
            'title' => 'Original Title',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $owner->id,
            'token' => Str::random(32),
        ]);

        $response = $this->actingAs($member)->put(route('surveys.update', $survey), [
            'title' => 'Hacked Title',
            'description' => 'Description',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addWeek()->format('Y-m-d'),
            'is_anonymous' => false,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('surveys', [
            'id' => $survey->id,
            'title' => 'Original Title',
        ]);
    }

    public function test_admin_can_delete_survey(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'admin']);

        $survey = Survey::create([
            'title' => 'To Delete',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => false,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => Str::random(32),
        ]);

        $response = $this->actingAs($user)->delete(route('surveys.destroy', $survey));

        $response->assertRedirect(route('surveys.index'));
        $this->assertDatabaseMissing('surveys', [
            'id' => $survey->id,
        ]);
    }

    public function test_survey_validation_requires_title(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'admin']);
        $user->update(['current_organization_id' => $organization->id]);

        $response = $this->actingAs($user)->post(route('surveys.store'), [
            'organization_id' => $organization->id,
            'title' => '',
            'description' => 'Description',
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addWeek()->format('Y-m-d'),
            'is_anonymous' => false,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_survey_end_date_must_be_after_start_date(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'admin']);
        $user->update(['current_organization_id' => $organization->id]);

        $response = $this->actingAs($user)->post(route('surveys.store'), [
            'organization_id' => $organization->id,
            'title' => 'Invalid Survey',
            'description' => 'Description',
            'start_date' => now()->addWeek()->format('Y-m-d'),
            'end_date' => now()->addDay()->format('Y-m-d'),
            'is_anonymous' => false,
        ]);

        $response->assertSessionHasErrors('end_date');
    }

    public function test_guest_can_access_survey_via_public_token(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);

        $token = Str::random(32);
        $survey = Survey::create([
            'title' => 'Public Survey',
            'description' => 'Description',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'is_anonymous' => true,
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'token' => $token,
        ]);

        $response = $this->get(route('public.survey.show', $token));

        $response->assertStatus(200);
        $response->assertSee('Public Survey');
    }
}
