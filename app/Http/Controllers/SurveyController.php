<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyQuestionAction;
use App\DTOs\SurveyQuestionDTO;
use App\Http\Requests\Survey\StoreSurveyQuestionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function store(StoreSurveyQuestionRequest $request, StoreSurveyQuestionAction $action)
    {
        // 1. Construction du DTO à partir de la requête validée
        $dto = SurveyQuestionDTO::fromRequest($request);
        // 2. Exécution de la logique métier via l’Action
        $article = $action->execute($dto);
        return view('layouts.questionShow' , ['questions' => $article]);
// 3. Réponse HTTP au format JSON
        /*return response()->json([
            'message' => 'Article créé avec succès.',
            'data' => $article,
        ], 201);
        */
    }
}
