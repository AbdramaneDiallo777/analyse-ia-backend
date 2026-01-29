Plateforme d’Analyse d’Avis Clients

API Laravel avec Intelligence Artificielle

Description du projet

Ce projet consiste à développer une plateforme complète permettant aux entreprises de collecter, gérer et analyser automatiquement les avis de leurs clients.
La solution repose sur une API REST développée avec Laravel et intègre un module d’Intelligence Artificielle chargé d’analyser le sentiment, la satisfaction et les thèmes récurrents présents dans les avis.

L’objectif est d’aider les entreprises à mieux comprendre la perception de leurs services ou produits à partir de données clients exploitables.

Contexte et objectifs

La plateforme vise à répondre aux besoins suivants :

Centraliser la gestion des avis clients via une API sécurisée.

Automatiser l’analyse des avis à l’aide d’un module d’IA.

Fournir des indicateurs clairs pour la prise de décision (statistiques, tendances, scores).

Offrir une architecture moderne, scalable et orientée API.

Architecture technique :

Backend : Laravel 12 (API REST uniquement)

Authentification : Laravel Sanctum (authentification par token)

Frontend : Vue.js 3 avec Vite et Axios

Équipe et rôles

Projet réalisé par le groupe :
DIALLO, ASSITAN, THIABA, NIANGADOU

Membre	Rôle	Responsabilités principales
Mamadou Niangadou	Lead Backend	Architecture API, gestion des avis, migrations, modèles, policies, validations
Membre B	Authentification et Dashboard	Sanctum, rôles utilisateurs, statistiques globales, tests
Membre C	Module IA	Service d’analyse, endpoints d’analyse, automatisation
Membre D	Frontend	Interface Vue 3, intégration API, vues (authentification, avis, dashboard)
Stack technique

Backend : Laravel 12

Base de données : MySQL ou MariaDB

Authentification : Laravel Sanctum

Frontend : Vue.js 3, Vite, Axios

Analyse IA : Service interne rule-based ou API externe

Fonctionnalités principales
1. Gestion des utilisateurs

Inscription et connexion sécurisées.

Déconnexion via token.

Récupération du profil utilisateur via l’endpoint /api/me.

Gestion des rôles : Administrateur et Utilisateur.

2. Gestion des avis clients

CRUD complet : création, lecture, mise à jour et suppression des avis.

Pagination et tri des avis (date, score, sentiment).

Filtres dynamiques pour l’analyse.

Validation des données (contenu obligatoire, longueur minimale, cohérence).

3. Module d’Intelligence Artificielle

Analyse automatique déclenchée lors de la création d’un avis.

Possibilité d’analyse manuelle via l’endpoint POST /api/analyze.

Données extraites :

Sentiment global : positif, négatif ou neutre.

Score de satisfaction sur une échelle de 0 à 100.

Thèmes principaux détectés (exemple : service, prix, qualité).

4. Tableau de bord analytique

Calcul de la note moyenne globale.

Répartition des avis par sentiment.

Identification des thèmes les plus récurrents.

Vue synthétique pour les administrateurs.

Installation et configuration (Backend)
Prérequis

PHP 8.2 ou supérieur

Composer

MySQL ou MariaDB

Node.js (pour le frontend si nécessaire)

Étapes d’installation

Cloner le dépôt

git clone https://github.com/VOTRE_REPO/analyse-ia-backend.git
cd analyse-ia-backend


Installer les dépendances PHP

composer install


Copier le fichier d’environnement

cp .env.example .env


Configurer la base de données dans le fichier .env

Générer la clé de l’application

php artisan key:generate


Lancer les migrations

php artisan migrate


Démarrer le serveur

php artisan serve
