<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Routes ajout de question
Route::get('/add-questions', function () {
    return view('layouts.addQuestionsSurvey');
});
Route::post('/add-questions', [SurveyController::class, 'store'])->name('question.store');

//Routes réponse aux questions
Route::get('/{survey}/answer_question', [SurveyController::class, 'index'])->name('surveys.index');

Route::post('/answer_question', [SurveyController::class, 'storeAnswer'])->name('answer.store');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// routes/web.php

require __DIR__.'/auth.php';
