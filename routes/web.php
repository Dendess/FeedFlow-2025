<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationUserController; // N'oublie pas cet import !
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Répondre à un sondage public (via token)
Route::get('/s/{token}', [SurveyController::class, 'publicShow'])
    ->name('public.survey.show');

// Route publique pour enregistrer une réponse : ajouter le controller dédié si nécessaire
// Route::post('/s/{token}', [SurveyAnswerController::class, 'store'])->name('public.survey.store');


/*
|--------------------------------------------------------------------------
| Routes Authentifiées (Dashboard & Gestion)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('surveys')->name('surveys.')->group(function () {
        // Questions du sondage
        Route::view('/questions/create', 'layouts.addQuestionsSurvey')->name('questions.create');
        Route::post('/questions', [SurveyController::class, 'storeSurveyQuestion'])->name('questions.store');

        // CRUD principal
        Route::get('/', [SurveyController::class, 'index'])->name('index');
        Route::get('/create', [SurveyController::class, 'create'])->name('create');
        Route::post('/', [SurveyController::class, 'store'])->name('store');

        // Réponses internes
        Route::get('/{survey}/answer', [SurveyController::class, 'indexAnswer'])
            ->name('answers.form')
            ->whereNumber('survey');
        Route::post('/answers', [SurveyController::class, 'storeAnswer'])->name('answers.store');

        // Détails / édition / résultats
        Route::get('/{survey}/results', [SurveyController::class, 'results'])
            ->name('results')
            ->whereNumber('survey');
        Route::get('/{survey}/edit', [SurveyController::class, 'edit'])
            ->name('edit')
            ->whereNumber('survey');
        Route::put('/{survey}', [SurveyController::class, 'update'])
            ->name('update')
            ->whereNumber('survey');
        Route::delete('/{survey}', [SurveyController::class, 'destroy'])
            ->name('destroy')
            ->whereNumber('survey');
        Route::get('/{survey}', [SurveyController::class, 'show'])
            ->name('show')
            ->whereNumber('survey');
    });

    // JSON endpoint: list surveys for an organization (used by the question-creator UI)
    Route::get('/organizations/{organization}/surveys/json', function (\App\Models\Organization $organization) {
        return response()->json($organization->surveys()->select('id', 'title')->get());
    })->name('organizations.surveys.json');

    Route::get('/organizations/{organization}/surveys/{survey}/questions/{question}/answers', [SurveyController::class, 'displayAnswer'])
        ->name('surveys.answers.stats')
        ->whereNumber('organization')
        ->whereNumber('survey')
        ->whereNumber('question');

    // --- Dashboard ---
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- Profil ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Organisations ---
    Route::resource('organizations', OrganizationController::class);
    
    Route::post('/organizations/{organization}/switch', [OrganizationController::class, 'switch'])
        ->name('organizations.switch');

    // JSON: organisations de l'utilisateur connecté (utilisé par l'UI de création de questions)
    Route::get('/my/organizations/json', function () {
        $user = auth()->user();
        if (! $user) return response()->json([], 401);
        $orgs = $user->organizations()->select('id', 'name')->get();
        return response()->json($orgs);
    })->name('my.organizations.json');

    Route::get('/my/organizations/search', function (Request $request) {
        $user = auth()->user();
        if (! $user) {
            return response()->json([], 401);
        }

        $query = $request->query('q', '');
        $organizationsQuery = $user->organizations()
            ->wherePivot('role', 'admin')
            ->select('organizations.id', 'organizations.name')
            ->orderBy('organizations.name');
        
        if (!empty($query)) {
            $organizationsQuery->where('organizations.name', 'like', '%'.$query.'%');
        }
        
        $matches = $organizationsQuery->limit(50)->get();

        return response()->json($matches);
    })->name('my.organizations.search');

    // --- Utilisateurs de l'Organisation ---
    Route::post('/organizations/{organization}/users', [OrganizationUserController::class, 'store'])
        ->name('organizations.users.store');

    Route::delete('/organizations/{organization}/users/{user}', [OrganizationUserController::class, 'destroy'])
        ->name('organizations.users.destroy');
});


require __DIR__.'/auth.php';