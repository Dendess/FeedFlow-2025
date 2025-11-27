<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationUserController; // N'oublie pas cet import !
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

// Enregistrer la réponse (Action publique) - À décommenter si tu as le Controller dédié
// Route::post('/s/{token}', [SurveyAnswerController::class, 'store'])->name('public.survey.store');


/*
|--------------------------------------------------------------------------
| Routes Authentifiées (Dashboard & Gestion)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // --- Dashboard ---
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- Profil ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Sondages (Survey) ---
    // On définit manuellement pour être sûr de l'ordre et des noms
    
    // 1. Liste et Création
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');

    // 2. Actions spécifiques sur un sondage
    Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/surveys/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
    Route::put('/surveys/{survey}', [SurveyController::class, 'update'])->name('surveys.update'); // ou PATCH
    Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
    
    // 3. Résultats (Story 9)
    Route::get('/surveys/{survey}/results', [SurveyController::class, 'results'])->name('surveys.results');

    // --- Organisations ---
    Route::resource('organizations', OrganizationController::class);
    
    Route::post('/organizations/{organization}/switch', [OrganizationController::class, 'switch'])
        ->name('organizations.switch');

    // --- Utilisateurs de l'Organisation ---
    Route::post('/organizations/{organization}/users', [OrganizationUserController::class, 'store'])
        ->name('organizations.users.store');

    Route::delete('/organizations/{organization}/users/{user}', [OrganizationUserController::class, 'destroy'])
        ->name('organizations.users.destroy');
});


require __DIR__.'/auth.php';