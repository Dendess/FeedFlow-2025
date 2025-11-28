<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\SurveyQuestion;

class SurveyQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $surveys = Survey::all();

        if ($surveys->isEmpty()) {
            $this->command->warn('Aucun sondage trouvé. Créez d\'abord des sondages.');
            return;
        }

        $questions = [
            [
                'title' => 'Comment évaluez-vous la qualité globale de nos services ?',
                'question_type' => 'scale',
                'options' => ['min' => 1, 'max' => 10],
            ],
            [
                'title' => 'Quel est votre niveau de satisfaction concernant notre support client ?',
                'question_type' => 'option',
                'options' => ['Très insatisfait', 'Insatisfait', 'Neutre', 'Satisfait', 'Très satisfait'],
            ],
            [
                'title' => 'Quelles fonctionnalités utilisez-vous le plus souvent ?',
                'question_type' => 'option',
                'options' => ['Tableau de bord', 'Rapports', 'Gestion des utilisateurs', 'Paramètres', 'API'],
            ],
            [
                'title' => 'Avez-vous des suggestions d\'amélioration ?',
                'question_type' => 'text',
                'options' => null,
            ],
            [
                'title' => 'Recommanderiez-vous notre service à un collègue ?',
                'question_type' => 'scale',
                'options' => ['min' => 0, 'max' => 10],
            ],
            [
                'title' => 'Quelle est la fréquence d\'utilisation de notre plateforme ?',
                'question_type' => 'option',
                'options' => ['Quotidienne', 'Hebdomadaire', 'Mensuelle', 'Occasionnelle', 'Rare'],
            ],
            [
                'title' => 'Décrivez en quelques mots votre expérience avec notre produit',
                'question_type' => 'text',
                'options' => null,
            ],
            [
                'title' => 'Dans quelle mesure notre produit répond-il à vos besoins ?',
                'question_type' => 'scale',
                'options' => ['min' => 1, 'max' => 5],
            ],
        ];

        foreach ($surveys as $survey) {
            $this->command->info("Ajout de questions au sondage: {$survey->title}");
            
            // Ajouter 3-6 questions aléatoires par sondage
            $numQuestions = rand(3, 6);
            $selectedQuestions = collect($questions)->random($numQuestions);
            
            foreach ($selectedQuestions as $index => $questionData) {
                SurveyQuestion::create([
                    'survey_id' => $survey->id,
                    'title' => $questionData['title'],
                    'question_type' => $questionData['question_type'],
                    'options' => isset($questionData['options']) ? json_encode($questionData['options']) : json_encode([]),
                ]);
            }
        }

        $this->command->info('Questions ajoutées avec succès!');
    }
}
