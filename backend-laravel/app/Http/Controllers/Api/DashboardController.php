<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Récupérer tous les avis
        $reviews = Review::all();
        $total = $reviews->count();

        // Si pas d'avis, on renvoie des zéros pour éviter la division par zéro
        if ($total === 0) {
            return response()->json([
                'average_score' => 0,
                'total_reviews' => 0,
                'sentiment_distribution' => ['positive' => 0, 'neutral' => 0, 'negative' => 0],
                'top_topics' => []
            ]);
        }

        // 2. Calculer la moyenne des scores
        $averageScore = round($reviews->avg('score'), 1);

        // 3. Calculer la répartition (Combien de positifs, neutres, etc.)
        $distribution = [
            'positive' => $reviews->where('sentiment', 'positive')->count(),
            'neutral'  => $reviews->where('sentiment', 'neutral')->count(),
            'negative' => $reviews->where('sentiment', 'negative')->count(),
        ];

        // 4. Trouver les Tops Thèmes (Un peu de logique PHP)
        $allTopics = [];
        foreach ($reviews as $review) {
            if ($review->topics) {
                foreach ($review->topics as $topic) {
                    if (!isset($allTopics[$topic])) {
                        $allTopics[$topic] = 0;
                    }
                    $allTopics[$topic]++;
                }
            }
        }
        // Trier pour avoir les plus fréquents en premier et garder le top 3
        arsort($allTopics);
        $topTopics = array_slice(array_keys($allTopics), 0, 3);

        return response()->json([
            'average_score' => $averageScore,
            'total_reviews' => $total,
            'sentiment_distribution' => $distribution,
            'top_topics' => $topTopics
        ]);
    }
}