<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewAnalyzer;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // POST /api/reviews
    public function store(Request $request, ReviewAnalyzer $analyzer)
    {
        // 1. On valide que le texte est bien là
        $validated = $request->validate([
            'content' => 'required|string|min:5', // Minimum 5 caractères [cite: 149]
        ]);

        // 2. On appelle notre Service IA pour analyser le texte
        $analysis = $analyzer->analyze($validated['content']);

        // 3. On sauvegarde tout en base
        // NOTE: Pour l'instant on met user_id à 1 en dur pour tester sans auth
        // Le membre B changera ça plus tard par $request->user()->id
        $review = Review::create([
            'user_id' => 1, 
            'content' => $validated['content'],
            'sentiment' => $analysis['sentiment'],
            'score' => $analysis['score'],
            'topics' => $analysis['topics'],
        ]);

        return response()->json($review, 201);
    }

    // GET /api/reviews
    public function index()
    {
        // Retourne la liste des avis, du plus récent au plus vieux
        return Review::latest()->get();
    }

    // GET /api/reviews/{id} - Voir un seul avis
    public function show(Review $review)
    {
        return $review;
    }

    // DELETE /api/reviews/{id} - Supprimer un avis
    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Avis supprimé avec succès']);
    }
}