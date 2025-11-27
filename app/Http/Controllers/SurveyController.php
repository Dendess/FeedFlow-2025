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
        $dto = SurveyQuestionDTO::fromRequest($request);
        // 2. Exécution de la logique métier via l’Action
        $article = $action->execute($dto);
        return view('layouts.questionShow' , ['questions' => $article]);
// 3. Réponse HTTP au format JSON
    }

    public function storeAnswer(StoreSurveyAnswerRequest $request, StoreSurveyAnswerAction $action)
    {
// 1. Construction du DTO à partir de la requête validée
        //dd($request->all());
        $answers = $request->input('answers');
        $survey_id = $answers[0]['survey_id'];
        foreach ($request->validated()['answers'] as $answerData) {
            $dto = SurveyAnswerDTO::fromArray($answerData,$request);

            // 2. Exécution de la logique métier via l’Action
            $action->execute($dto);
        }
// 3. Réponse HTTP au format JSON
        return redirect()->route('surveys.index', ['survey' => $survey_id])
            ->with('success', 'Merci pour votre réponse!');
    }
}
