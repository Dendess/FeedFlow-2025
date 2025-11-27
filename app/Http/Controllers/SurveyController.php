<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAnswerAction;
use App\Actions\Survey\StoreSurveyQuestionAction;
use App\DTOs\SurveyAnswerDTO;
use App\DTOs\SurveyQuestionDTO;
use App\Http\Requests\Survey\StoreSurveyAnswerRequest;
use App\Http\Requests\Survey\StoreSurveyQuestionRequest;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(int $survey_id)
    {
        $questions = SurveyQuestion::where('survey_id', $survey_id)->get();   // FETCH DB
        return view('layouts.AnswerQuestion', compact('questions','survey_id'));  // BLADE
    }
    public function store(StoreSurveyQuestionRequest $request, StoreSurveyQuestionAction $action)
    {
// 1. Construction du DTO à partir de la requête validée
        dd($request->all());
        $dto = SurveyQuestionDTO::fromRequest($request);
// 2. Exécution de la logique métier via l’Action
        $question = $action->execute($dto);
// 3. Réponse HTTP au format JSON
        return redirect()
            ->back()
            ->with('success', 'Réponse enregistrée avec succès.');
    }

    public function storeAnswer(StoreSurveyAnswerRequest $request, StoreSurveyAnswerAction $action)
    {
// 1. Construction du DTO à partir de la requête validée
        //dd($request->all());
        $answers = $request->input('answers');
        $survey_id = $answers[0]['survey_id'];
        foreach ($request->validated()['answers'] as $answerData) {
            if (is_array($answerData['answer'])){
                $answerData['answer'] = json_encode($answerData['answer']);
            }
            $dto = SurveyAnswerDTO::fromArray($answerData,$request);

            // 2. Exécution de la logique métier via l’Action
            $action->execute($dto);
        }
// 3. Réponse HTTP au format JSON
        return redirect()->route('surveys.index', ['survey' => $survey_id])
            ->with('success', 'Merci pour votre réponse!');
    }
}
