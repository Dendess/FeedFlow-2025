<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Astuce : On passe les organisations à la vue dashboard pour le menu
    // (ou on utilise un ViewComposer plus tard)
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Routes ajout de question
Route::get('{organization}/{survey_id}/add-questions', function ($organization, $survey_id) {
    $questions = SurveyQuestion::where('survey_id' , $survey_id)
        ->get();
    return view('layouts.addQuestionsSurvey', [
        'questions' => $questions,
        'organization' => $organization,
        'survey_id'    => $survey_id,
        ]);
});
Route::post('{organization}/{survey_id}/add-questions', [SurveyController::class, 'store'])->name('question.store');

//Routes réponse aux questions
Route::get('/{survey}/answer_question', [SurveyController::class, 'indexAnswer'])->name('surveys.index');

Route::get('{organization}/{survey}/{question_id}/display_question', [SurveyController::class, 'displayAnswer'])->name('surveys.stats');

Route::post('/answer_question', [SurveyController::class, 'storeAnswer'])->name('answer.store');


Route::post('/add-questions', [SurveyController::class, 'storeSurveyQuestion'])->name('question.store');


Route::middleware('auth')->group(function () {
    // Routes du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2. Routes Sondages (Survey)
    // On utilise '/surveys' (pluriel) par convention REST, et on pointe vers le contrôleur
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
    Route::put('/surveys/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');

    // Routes imbriquées : organizations/{id}/users
    Route::post('/organizations/{organization}/users', [OrganizationUserController::class, 'store'])
        ->name('organizations.users.store');

    Route::delete('/organizations/{organization}/users/{user}', [OrganizationUserController::class, 'destroy'])
        ->name('organizations.users.destroy');

    // --- Routes de l'Organisation ---

    // 1. Route Resource : Crée automatiquement index, create, store, show, edit, update, destroy
    Route::resource('organizations', OrganizationController::class);

    // 2. Route personnalisée pour le Switch
    Route::post('/organizations/{organization}/switch', [OrganizationController::class, 'switch'])
        ->name('organizations.switch');
});

Route::get('/survey/{token}', [SurveyController::class, 'publicShow'])
    ->name('public.survey.show');
require __DIR__.'/auth.php';
