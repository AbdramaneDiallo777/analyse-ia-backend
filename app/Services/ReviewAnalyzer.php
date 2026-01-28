<?php
namespace App\Services;

class ReviewAnalyzer
{
    // Dictionnaire étendu (racines de mots pour couvrir toutes les déclinaisons)
    private array $positiveWords = [
        // Qualité & Satisfaction
        'super', 'excellen', 'parfait', 'genial', 'top', 'formidable', 'extra', 'magnifique', 
        'incroyable', 'fanta', 'impeccable', 'merveille', 'bon', 'bien', 'agreable', 'nickel', 
        'ravie', 'satisfait', 'content', 'ador', 'kiffe', 'aime', 'recommande', 'reussit', 
        'topissime', 'parfaitement', 'propre', 'soigne', 'qualite', 'solide', 'robuste',
        // Service & Rapidité
        'rapid', 'vite', 'efficace', 'reactif', 'pro', 'sympa', 'accueillant', 'aimable', 
        'serieux', 'fiable', 'ponctuel', 'ecoute', 'disponible', 'serviable', 'geniaux',
        // Prix
        'economique', 'abordable', 'cadeau', 'aubaine', 'rentable', 'correct', 'honnete'
    ];

    private array $negativeWords = [
        // Déception & Colère
        'horrible', 'mauvais', 'nul', 'pourri', 'mediocre', 'pitoyable', 'decu', 'decevant',
        'catastrophique', 'dommage', 'zero', 'naze', 'minable', 'regrette', 'honte', 'fuir',
        'eviter', 'pire', 'mauvaise', 'terrible', 'affreux', 'degout', 'beurk', 'nase',
        // Problèmes Techniques
        'casse', 'abime', 'defectueux', 'panne', 'dysfonctionnement', 'bug', 'erreur',
        'probleme', 'manquant', 'vide', 'incomplet', 'fragile', 'pourri', 'sale',
        // Service & Temps
        'lent', 'long', 'attente', 'retard', 'jamais', 'perdu', 'incompetent', 'impoli', 
        'arrogant', 'grossier', 'desagreable', 'foutage', 'arnaque', 'escroc', 'vol',
        // Prix
        'cher', 'onereux', 'excessif', 'surpaye', 'aberrant', 'abus', 'voler'
    ];

    private array $intensifiers = [
        'tres', 'vraiment', 'tellement', 'hyper', 'extremement', 'grave', 'trop', 
        'super', 'mega', 'ultra', 'particulierement', 'incroyablement'
    ];

    private array $negators = [
        'pas', 'ne', 'aucun', 'jamais', 'rien', 'nullement', 'guere', 'pas du tout', 'ni'
    ];

    private array $contrastWords = ['mais', 'cependant', 'pourtant', 'toutefois', 'revanche'];

    public function analyze(string $text): array
    {
        $cleanText = $this->normalizeText($text);
        $words = preg_split('/[\s,.\'!?]+/', $cleanText, -1, PREG_SPLIT_NO_EMPTY);
        
        $score = 50; 
        $contrastFound = false;

        foreach ($words as $index => $word) {
            $multiplier = 1.0;
            
            if (in_array($word, $this->contrastWords)) {
                $contrastFound = true;
                continue;
            }

            // Vérification de négation (fenêtre de 2 mots : "n'est vraiment pas bon")
            $isNegated = false;
            for ($i = 1; $i <= 3; $i++) {
                if ($index - $i >= 0 && in_array($words[$index - $i], $this->negators)) {
                    $isNegated = true;
                }
            }

            // Boost par intensificateur
            if ($index > 0 && in_array($words[$index - 1], $this->intensifiers)) {
                $multiplier = 2.0;
            }

            // Poids doublé après un "mais" (ex: "Rapide mais CHER")
            if ($contrastFound) $multiplier *= 1.5;

            // Analyse Positive
            foreach ($this->positiveWords as $pos) {
                if (str_starts_with($word, $pos)) {
                    $val = 15 * $multiplier;
                    if ($isNegated) {
                        $score -= ($val * 1.2); // "pas bon" est très négatif
                    } else {
                        $score += $val;
                    }
                    break;
                }
            }

            // Analyse Négative
            foreach ($this->negativeWords as $neg) {
                if (str_starts_with($word, $neg)) {
                    $val = 20 * $multiplier; // Le négatif pèse plus lourd dans le score
                    if ($isNegated) {
                        $score += ($val * 0.8); // "pas cher" est positif
                    } else {
                        $score -= $val;
                    }
                    break;
                }
            }
        }

        $score = max(0, min(100, $score));

        // Seuils de sentiment
        $sentiment = 'neutral';
        if ($score > 62) $sentiment = 'positive';
        elseif ($score < 38) $sentiment = 'negative';

        return [
            'sentiment' => $sentiment,
            'score'     => (int)$score,
            'topics'    => $this->detectTopics($cleanText),
            'emoji'     => $this->getEmoji($sentiment),
            'text'      => $text
        ];
    }

    private function normalizeText(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $utf8 = [
            '/[áàâãªä]/u' => 'a', '/[éèêë]/u' => 'e', '/[íìîï]/u' => 'i',
            '/[óòôõºö]/u' => 'o', '/[úùûü]/u' => 'u', '/[ç]/u' => 'c'
        ];
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

    private function detectTopics(string $text): array
    {
        $found = [];
        $rules = [
            'Livraison' => ['livraison', 'colis', 'recu', 'envoi', 'transport', 'delai', 'arrive', 'boite', 'paquet', 'facteur'],
            'Prix'      => ['prix', 'tarif', 'cher', 'cout', 'paye', 'argent', 'euro', 'budget', 'onereux', 'promo', 'solde'],
            'Produit'   => ['produit', 'qualite', 'solide', 'materiau', 'objet', 'article', 'beau', 'belle', 'finition', 'aspect'],
            'Service'   => ['service', 'accueil', 'vendeur', 'conseil', 'equipe', 'personnel', 'agent', 'sav', 'contact', 'appel'],
            'Efficacité' => ['rapide', 'vite', 'efficace', 'marche', 'fonctionne', 'installation', 'reglage', 'simple', 'facile']
        ];

        foreach ($rules as $topic => $keywords) {
            foreach ($keywords as $key) {
                if (str_contains($text, $key)) {
                    $found[] = $topic;
                    break;
                }
            }
        }
        
        return empty($found) ? ['Général'] : array_unique($found);
    }

    private function getEmoji($sentiment): string {
        return match($sentiment) {
            'positive' => ' Bravo',
            'negative' => ' Dommage',
            default    => ' A voir',
        };
    }
}