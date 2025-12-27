<?php

namespace App\Services;

class ReviewAnalyzer
{
    /**
     * Analyse un texte et retourne le sentiment, le score et les thèmes.
     */
    public function analyze(string $text): array
    {
        $textLower = strtolower($text);
        
        // 1. Détection simple du sentiment
       // Dans ReviewAnalyzer.php
         $positiveWords = ['super', 'excellent', 'bon', 'rapide', 'top', 'génial', 'adore', 'parfait'];
        $negativeWords = ['mauvais', 'lent', 'nul', 'cher', 'problème', 'déçu', 'horrible'];
        
        $posCount = 0;
        $negCount = 0;

        foreach ($positiveWords as $word) {
            if (str_contains($textLower, $word)) $posCount++;
        }
        foreach ($negativeWords as $word) {
            if (str_contains($textLower, $word)) $negCount++;
        }

        // Calcul du sentiment
        $sentiment = 'neutral';
        $score = 50; // Score de base

        if ($posCount > $negCount) {
            $sentiment = 'positive';
            $score = 80 + ($posCount * 5);
        } elseif ($negCount > $posCount) {
            $sentiment = 'negative';
            $score = 40 - ($negCount * 5);
        }
        
        // On garde le score entre 0 et 100
        $score = max(0, min(100, $score));

        // 2. Détection des thèmes (Topics)
        $topics = [];
        if (str_contains($textLower, 'livra') || str_contains($textLower, 'colis') || str_contains($textLower, 'reçu')) {
            $topics[] = 'livraison';
        }
        if (str_contains($textLower, 'prix') || str_contains($textLower, 'tarif') || str_contains($textLower, '€')) {
            $topics[] = 'prix';
        }
        if (str_contains($textLower, 'qualit') || str_contains($textLower, 'produit')) {
            $topics[] = 'qualité';
        }

        return [
            'sentiment' => $sentiment,
            'score' => $score,
            'topics' => $topics
        ];
    }
}