##Technical Lead : Architecture & Security (Membre B) Abdramane Diallo

Ce volet du projet documente la conception de l'infrastructure, la sécurisation des données et l'orchestration des services critiques. Le Membre B a assuré le rôle de garant de la stabilité et de l'intégrité du système.
##1. Architecture Système & API Design

## L'architecture a été conçue selon un modèle decoupled (headless) pour séparer strictement la logique métier de la présentation.

    API RESTful : Implémentation d'une API Stateless avec Laravel 11. Toutes les réponses sont normalisées au format JSON avec les codes d'état HTTP appropriés (200, 201, 401, 422).

    Layered Architecture : Introduction d'une couche de Services (App\Services) pour isoler l'algorithme NLP du ReviewController, facilitant ainsi la maintenance et les tests unitaires.

    Gestion des Dépendances : Orchestration via Composer (PHP) et intégration des processus Python pour l'analyse de données.

##2. Sécurité et Authentification (Laravel Sanctum)

Le Membre B a mis en place une stratégie de sécurité multicouche pour protéger les données sensibles des utilisateurs.

    Authentification par Token Bearer : Abandon des sessions traditionnelles au profit de Laravel Sanctum. Chaque requête sécurisée est validée via un jeton unique stocké par le client.

    Middlewares de Protection : Configuration du groupe de middleware auth:sanctum pour restreindre l'accès aux fonctionnalités de stockage, d'historique et de génération de rapports.

    Validation de Schéma : Implémentation de FormRequests pour intercepter et valider les données entrantes avant qu'elles n'atteignent les couches métier.

##3. Orchestration des Processus 

L'une des réalisations techniques majeures est le pont établi entre Laravel et le moteur graphique Python.

    Service de Processus : Utilisation de la façade Illuminate\Support\Facades\Process pour exécuter audit_viz.CSV.

    Data Streaming : Les données de la base SQLite sont extraites, converties en JSON par Laravel, puis transmises au script Python via l'entrée standard (stdin).

    Filesystem Integration : Gestion du stockage des graphiques générés dans le répertoire storage/app/public avec création d'un lien symbolique pour permettre un accès URL via le Frontend.

##4. Infrastructure de Données & Modélisation

Conception d'une base de données SQLite optimisée pour l'analyse de texte.

    Migrations Atomiques : Création de structures de tables robustes avec contraintes d'intégrité (clés étrangères avec onDelete('cascade')).

    Optimisation JSON : Utilisation du type de colonne json pour le champ topics, permettant de stocker des métadonnées complexes sans multiplier les tables pivots.

    Eloquent Casting : Configuration du modèle Review.php pour transformer automatiquement les données JSON en tableaux PHP ('topics' => 'array').

## Stack Technique (Maîtrisée par le Membre B)

    Backend : Laravel 11, PHP 8.2+

    Sécurité : Sanctum, CORS Policy

    Base de données : SQLite

---

##  Module : Authentification & Sécurité (Réalisé par Membre B)

Cette branche contient l'implémentation de la couche de sécurité du projet utilisant **Laravel Sanctum**. L'objectif est de sécuriser les échanges entre le front-end et l'API via des jetons (tokens).

###  Ce qui a été fait :
- **Installation de Laravel Sanctum** : Configuration du système d'authentification par jeton.
- **AuthController** : Création du contrôleur gérant :
  - `register()` : Inscription des nouveaux utilisateurs.
  - `login()` : Vérification des identifiants et génération du token.
  - `logout()` : Révocation du token pour une déconnexion sécurisée.
- **Routes API** : Mise en place des points d'accès sécurisés dans `routes/api.php`.
- **Middleware** : Protection des routes sensibles pour n'autoriser que les utilisateurs connectés.

---

###  Instructions pour l'équipe (Installation)

Pour tester ma partie sur votre machine, suivez ces étapes :

1. **Mettre à jour les dépendances :**
   ```bash
   composer install
