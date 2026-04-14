<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use const PHPSTORM_META\ANY_ARGUMENT;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:api','role:candidat'])->group(function(){
    Route::post('/profil', [ProfilController::class, 'store']);
    Route::get('/profil', [ProfilController::class, 'show']);
    Route::put('/profil', [ProfilController::class, 'update']);
    Route::post('/profil/competences', [ProfilController::class, 'addCompetence']);
    Route::delete('/profil/competences/{competence}', [ProfilController::class, 'removeCompetence']);
});

Route::middleware(['auth:api'])->group(function(){
    Route::get('/offres',[OffreController::class, 'index']);
    Route::get('/offres/{offre}',[OffreController::class, 'show']);

    Route::middleware(['auth:api', 'role:recruteur'])->group(function(){
        Route::post('/offres', [OffreController::class,'store']);
        Route::put('/offres/{offre}', [OffreController::class, 'update']);
        Route::delete('/offres/{offre}', [OffreController::class, 'destroy']);
    });
});

Route::middleware(['auth:api'])->group(function(){
    Route::middleware(['role:candidat'])->group(function(){
        Route::post('/offres/{offre}/candidater', [CandidatureController::class, 'store']);
        Route::get('/mes-candidatures', [CandidatureController::class, 'index']);
    });
    Route::middleware(['role:recruteur'])->group(function(){
        Route::get('/offres/{offre}/candidatures', [CandidatureController::class, 'candidaturesOffres']);
        Route::patch('/candidatures/{candidature}/statut', [CandidatureController::class, 'updateStatut']);
    });
});

Route::middleware(['auth:api', 'role:admin'])->group(function(){
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::delete('/admin/users/{user}', [AdminController::class, 'delete']);
    Route::patch('/admin/offres/{offre}',[AdminController::class, 'update']);
});