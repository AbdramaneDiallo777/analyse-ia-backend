<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importation des contrôleurs du sous-dossier Api
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\AnalyzeController;


// --- ROUTES PUBLIQUES ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Endpoint IA obligatoire (Analyse sans enregistrement)
Route::post('/analyze', AnalyzeController::class);

// --- ROUTES PROTÉGÉES (Auth Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {

    // Gestion de l'utilisateur (Profil)
    Route::get('/user', [AuthController::class, 'me']);
    Route::patch('/user', [AuthController::class, 'updateProfile']);
    Route::patch('/user/password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Gestion des Avis (CRUD Complet)
    Route::apiResource('reviews', ReviewController::class);

    // Rapport visuel (Script Python)
    Route::get('/reviews/visual-report', [ReviewController::class, 'generateVisualReport']);

    // Dashboard Statistiques
    Route::get('/dashboard/stats', function() {
        $reviews = \App\Models\Review::all();
        $count = $reviews->count();
        
        return response()->json([
            'total_reviews'    => $count,
            'avg_score'        => round($reviews->avg('score') ?? 0, 2),
            'positive_percent' => $count > 0 ? round(($reviews->where('sentiment', 'positive')->count() / $count) * 100, 1) : 0,
            'negative_percent' => $count > 0 ? round(($reviews->where('sentiment', 'negative')->count() / $count) * 100, 1) : 0
        ]);
    });
});