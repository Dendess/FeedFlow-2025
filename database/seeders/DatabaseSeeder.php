<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

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
            'email'         => 'adam@honvault.com',
            'password'      => bcrypt('password'),
        ]);
        Organization::create([
            'name' => 'Example Organization',
            'user_id' => 1,
        ]);

        Survey::create([
            'organization_id' => 1, // adjust if you have organizations
            'user_id'         => 1, // must be a valid user ID
            'title'           => 'Customer Satisfaction Survey',
            'description'     => 'A short survey to collect customer feedback on our services.',
            'start_date'      => Carbon::now()->subDays(1),
            'end_date'        => Carbon::now()->addDays(30),
            'is_anonymous'    => true,
            'token'           => 'Damn',
        ]);
    }
}
