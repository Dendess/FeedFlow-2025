<?php

use App\Http\Controllers\OrganizationController; // <--- Import indispensable
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Astuce : On passe les organisations à la vue dashboard pour le menu
    // (ou on utilise un ViewComposer plus tard)
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Routes du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Routes de l'Organisation ---
    
    // 1. Route Resource : Crée automatiquement index, create, store, show, edit, update, destroy
    Route::resource('organizations', OrganizationController::class);

    // 2. Route personnalisée pour le Switch
    Route::post('/organizations/{organization}/switch', [OrganizationController::class, 'switch'])
        ->name('organizations.switch');
});

require __DIR__.'/auth.php';