<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyAnswer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===== USERS =====
        $john = User::create([
            'last_name'  => 'Doe',
            'first_name' => 'John',
            'email'      => 'john.doe@feedflow.local',
            'password'   => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $jane = User::create([
            'last_name'  => 'Smith',
            'first_name' => 'Jane',
            'email'      => 'jane.smith@feedflow.local',
            'password'   => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $bob = User::create([
            'last_name'  => 'Martin',
            'first_name' => 'Bob',
            'email'      => 'bob.martin@feedflow.local',
            'password'   => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $alice = User::create([
            'last_name'  => 'Johnson',
            'first_name' => 'Alice',
            'email'      => 'alice.johnson@feedflow.local',
            'password'   => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $charlie = User::create([
            'last_name'  => 'Brown',
            'first_name' => 'Charlie',
            'email'      => 'charlie.brown@feedflow.local',
            'password'   => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // ===== ORGANIZATIONS =====
        $techCorp = Organization::create([
            'name'    => 'TechCorp Solutions',
            'user_id' => $john->id,
        ]);

        $greenEnergy = Organization::create([
            'name'    => 'Green Energy Inc',
            'user_id' => $jane->id,
        ]);

        $eduLearn = Organization::create([
            'name'    => 'EduLearn Academy',
            'user_id' => $bob->id,
        ]);

        $healthPlus = Organization::create([
            'name'    => 'Health Plus Clinic',
            'user_id' => $alice->id,
        ]);

        // Attach users to organizations with different roles
        $techCorp->users()->attach([
            $john->id => ['role' => 'admin'],
            $jane->id => ['role' => 'admin'],
            $bob->id => ['role' => 'member'],
        ]);

        $greenEnergy->users()->attach([
            $jane->id => ['role' => 'admin'],
            $alice->id => ['role' => 'member'],
            $charlie->id => ['role' => 'member'],
        ]);

        $eduLearn->users()->attach([
            $bob->id => ['role' => 'admin'],
            $john->id => ['role' => 'member'],
        ]);

        $healthPlus->users()->attach([
            $alice->id => ['role' => 'admin'],
            $charlie->id => ['role' => 'admin'],
        ]);

        // ===== SURVEYS =====
        
        // Survey 1: Customer Satisfaction (TechCorp)
        $satisfactionSurvey = Survey::create([
            'organization_id' => $techCorp->id,
            'user_id'         => $john->id,
            'title'           => 'Customer Satisfaction Survey 2025',
            'description'     => 'Help us improve our services by sharing your feedback on your recent experience with TechCorp Solutions.',
            'start_date'      => Carbon::now()->subDays(10),
            'end_date'        => Carbon::now()->addDays(20),
            'is_anonymous'    => false,
            'token'           => Str::random(32),
        ]);

        // Questions for Survey 1
        $q1 = SurveyQuestion::create([
            'survey_id'     => $satisfactionSurvey->id,
            'title'         => 'How satisfied are you with our service?',
            'question_type' => 'scale',
            'options'       => json_encode(['1', '2', '3', '4', '5']),
        ]);

        $q2 = SurveyQuestion::create([
            'survey_id'     => $satisfactionSurvey->id,
            'title'         => 'Which product did you use?',
            'question_type' => 'option',
            'options'       => json_encode(['Cloud Hosting', 'Web Development', 'Mobile App', 'Consulting']),
        ]);

        $q3 = SurveyQuestion::create([
            'survey_id'     => $satisfactionSurvey->id,
            'title'         => 'What could we improve?',
            'question_type' => 'text',
            'options'       => json_encode([]),
        ]);

        // Answers for Survey 1
        SurveyAnswer::create([
            'survey_question_id' => $q1->id,
            'user_id'            => $bob->id,
            'answer'             => '5',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q2->id,
            'user_id'            => $bob->id,
            'answer'             => json_encode(['Cloud Hosting']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q3->id,
            'user_id'            => $bob->id,
            'answer'             => 'Great service! Maybe add more documentation.',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q1->id,
            'user_id'            => $jane->id,
            'answer'             => '4',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q2->id,
            'user_id'            => $jane->id,
            'answer'             => json_encode(['Web Development']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q3->id,
            'user_id'            => $jane->id,
            'answer'             => 'Response time could be faster.',
        ]);

        // Survey 2: Employee Engagement (TechCorp)
        $employeeSurvey = Survey::create([
            'organization_id' => $techCorp->id,
            'user_id'         => $john->id,
            'title'           => 'Employee Engagement Survey',
            'description'     => 'Anonymous survey to measure employee satisfaction and engagement levels.',
            'start_date'      => Carbon::now()->subDays(5),
            'end_date'        => Carbon::now()->addDays(25),
            'is_anonymous'    => true,
            'token'           => Str::random(32),
        ]);

        $q4 = SurveyQuestion::create([
            'survey_id'     => $employeeSurvey->id,
            'title'         => 'How satisfied are you with your work environment?',
            'question_type' => 'scale',
            'options'       => json_encode(['1', '2', '3', '4', '5']),
        ]);

        $q5 = SurveyQuestion::create([
            'survey_id'     => $employeeSurvey->id,
            'title'         => 'Which benefits matter most to you?',
            'question_type' => 'option',
            'options'       => json_encode(['Health Insurance', 'Remote Work', 'Paid Time Off', 'Training Budget']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q4->id,
            'user_id'            => null, // Anonymous
            'answer'             => '4',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q5->id,
            'user_id'            => null,
            'answer'             => json_encode(['Remote Work', 'Health Insurance']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q4->id,
            'user_id'            => null,
            'answer'             => '5',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q5->id,
            'user_id'            => null,
            'answer'             => json_encode(['Training Budget']),
        ]);

        // Survey 3: Climate Action Survey (Green Energy)
        $climateSurvey = Survey::create([
            'organization_id' => $greenEnergy->id,
            'user_id'         => $jane->id,
            'title'           => 'Climate Action Feedback',
            'description'     => 'We want to know your thoughts on renewable energy and sustainability initiatives.',
            'start_date'      => Carbon::now()->subDays(15),
            'end_date'        => Carbon::now()->addDays(15),
            'is_anonymous'    => false,
            'token'           => Str::random(32),
        ]);

        $q6 = SurveyQuestion::create([
            'survey_id'     => $climateSurvey->id,
            'title'         => 'How important is renewable energy to you?',
            'question_type' => 'scale',
            'options'       => json_encode(['1', '2', '3', '4', '5']),
        ]);

        $q7 = SurveyQuestion::create([
            'survey_id'     => $climateSurvey->id,
            'title'         => 'Which renewable sources do you prefer?',
            'question_type' => 'option',
            'options'       => json_encode(['Solar', 'Wind', 'Hydro', 'Geothermal']),
        ]);

        $q8 = SurveyQuestion::create([
            'survey_id'     => $climateSurvey->id,
            'title'         => 'Additional comments on sustainability',
            'question_type' => 'text',
            'options'       => json_encode([]),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q6->id,
            'user_id'            => $alice->id,
            'answer'             => '5',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q7->id,
            'user_id'            => $alice->id,
            'answer'             => json_encode(['Solar', 'Wind']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q8->id,
            'user_id'            => $alice->id,
            'answer'             => 'We need more investment in solar technology.',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q6->id,
            'user_id'            => $charlie->id,
            'answer'             => '4',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q7->id,
            'user_id'            => $charlie->id,
            'answer'             => json_encode(['Wind', 'Hydro']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q8->id,
            'user_id'            => $charlie->id,
            'answer'             => 'Hydro is underrated, we should explore it more.',
        ]);

        // Survey 4: Course Feedback (EduLearn)
        $courseSurvey = Survey::create([
            'organization_id' => $eduLearn->id,
            'user_id'         => $bob->id,
            'title'           => 'Online Course Feedback',
            'description'     => 'Share your experience with our online learning platform and courses.',
            'start_date'      => Carbon::now()->subDays(7),
            'end_date'        => Carbon::now()->addDays(23),
            'is_anonymous'    => false,
            'token'           => Str::random(32),
        ]);

        $q9 = SurveyQuestion::create([
            'survey_id'     => $courseSurvey->id,
            'title'         => 'Rate the course content quality',
            'question_type' => 'scale',
            'options'       => json_encode(['1', '2', '3', '4', '5']),
        ]);

        $q10 = SurveyQuestion::create([
            'survey_id'     => $courseSurvey->id,
            'title'         => 'Which topic did you study?',
            'question_type' => 'option',
            'options'       => json_encode(['Web Development', 'Data Science', 'Design', 'Marketing']),
        ]);

        $q11 = SurveyQuestion::create([
            'survey_id'     => $courseSurvey->id,
            'title'         => 'What features would you like to see?',
            'question_type' => 'text',
            'options'       => json_encode([]),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q9->id,
            'user_id'            => $john->id,
            'answer'             => '5',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q10->id,
            'user_id'            => $john->id,
            'answer'             => json_encode(['Web Development']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q11->id,
            'user_id'            => $john->id,
            'answer'             => 'More interactive exercises and live coding sessions.',
        ]);

        // Survey 5: Patient Satisfaction (Health Plus)
        $patientSurvey = Survey::create([
            'organization_id' => $healthPlus->id,
            'user_id'         => $alice->id,
            'title'           => 'Patient Satisfaction Survey',
            'description'     => 'Help us improve our healthcare services by sharing your recent visit experience.',
            'start_date'      => Carbon::now()->subDays(3),
            'end_date'        => Carbon::now()->addDays(27),
            'is_anonymous'    => true,
            'token'           => Str::random(32),
        ]);

        $q12 = SurveyQuestion::create([
            'survey_id'     => $patientSurvey->id,
            'title'         => 'How would you rate your overall experience?',
            'question_type' => 'scale',
            'options'       => json_encode(['1', '2', '3', '4', '5']),
        ]);

        $q13 = SurveyQuestion::create([
            'survey_id'     => $patientSurvey->id,
            'title'         => 'Which service did you use?',
            'question_type' => 'option',
            'options'       => json_encode(['General Checkup', 'Dental', 'Specialist Consultation', 'Emergency']),
        ]);

        $q14 = SurveyQuestion::create([
            'survey_id'     => $patientSurvey->id,
            'title'         => 'Any suggestions for improvement?',
            'question_type' => 'text',
            'options'       => json_encode([]),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q12->id,
            'user_id'            => null,
            'answer'             => '5',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q13->id,
            'user_id'            => null,
            'answer'             => json_encode(['General Checkup']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q14->id,
            'user_id'            => null,
            'answer'             => 'Very professional staff, keep up the good work!',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q12->id,
            'user_id'            => null,
            'answer'             => '4',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q13->id,
            'user_id'            => null,
            'answer'             => json_encode(['Dental']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q14->id,
            'user_id'            => null,
            'answer'             => 'Waiting time could be reduced.',
        ]);

        // Survey 6: Closed survey for testing
        $closedSurvey = Survey::create([
            'organization_id' => $techCorp->id,
            'user_id'         => $john->id,
            'title'           => 'Q4 2024 Feedback (Closed)',
            'description'     => 'This survey has ended. Thank you to everyone who participated.',
            'start_date'      => Carbon::now()->subDays(60),
            'end_date'        => Carbon::now()->subDays(30),
            'is_anonymous'    => false,
            'token'           => Str::random(32),
        ]);

        $q15 = SurveyQuestion::create([
            'survey_id'     => $closedSurvey->id,
            'title'         => 'Overall Q4 satisfaction',
            'question_type' => 'scale',
            'options'       => json_encode(['1', '2', '3', '4', '5']),
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q15->id,
            'user_id'            => $bob->id,
            'answer'             => '4',
        ]);

        SurveyAnswer::create([
            'survey_question_id' => $q15->id,
            'user_id'            => $jane->id,
            'answer'             => '5',
        ]);

        $this->command->info('✓ Database seeded successfully with comprehensive data!');
        $this->command->info('  - 5 Users created');
        $this->command->info('  - 4 Organizations created');
        $this->command->info('  - 6 Surveys created');
        $this->command->info('  - 15 Questions created');
        $this->command->info('  - 25+ Answers created');
    }
}
