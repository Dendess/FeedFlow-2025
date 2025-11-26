<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\Organization;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
            'last_name'     => 'Doe',
            'first_name'    => 'John',
            'email'         => 'test@feedflow.local',
            'password'      => bcrypt('password'),
        ]);

        Organization::create([
            'id' => 1,
            'name' => 'My Organization',
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 1
        ]);
        Survey::create([
            'organization_id' => 1,
            'user_id' => 1,
            'title' => 'Automatic Test Survey',
            'description' => 'Created automatically by seeder.',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_anonymous' => true,
            'token' => 'Zamn',
        ]);

        Survey::create([
            'organization_id' => 1,
            'user_id' => 1,
            'title' => 'Automatic Test Survey 2',
            'description' => 'Created automatically by seeder. 2',
            'start_date' => now()->subDay(),
            'end_date' => now()->subDay(),
            'is_anonymous' => false,
            'token' => 'Damn',
        ]);

        $survey = Survey::create([
            'organization_id' => 1,
            'user_id' => 1,
            'title' => 'Automatic Test Survey',
            'description' => 'Created automatically by seeder.',
            'token' => 'Zamn',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_anonymous' => true,
        ]);


    }
}
