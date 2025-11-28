<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Survey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HomerSurveySeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::find(8);

        if (! $organization) {
            $this->command?->warn('Organisation Homer (id=8) introuvable, aucun sondage créé.');
            return;
        }

        $payloads = [
            [
                'title' => 'Feedback produits Q1',
                'description' => 'Collecte des retours clients sur le catalogue du trimestre.',
                'start_date' => now()->addDay(),
                'end_date' => now()->addDays(7),
                'is_anonymous' => true,
            ],
            [
                'title' => 'Climat interne 2025',
                'description' => 'Mesure de la satisfaction des équipes Homer.',
                'start_date' => now()->addDays(2),
                'end_date' => now()->addDays(14),
                'is_anonymous' => false,
            ],
        ];

        foreach ($payloads as $data) {
            Survey::firstOrCreate(
                [
                    'organization_id' => $organization->id,
                    'title' => $data['title'],
                ],
                [
                    'description' => $data['description'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'is_anonymous' => $data['is_anonymous'],
                    'user_id' => $organization->user_id,
                    'token' => Str::random(32),
                ]
            );
        }

        $this->command?->info('Sondages de test ajoutés pour Homer (#8).');
    }
}
