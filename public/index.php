<?php
// Active le mode strict pour la vérification des types
declare(strict_types=1);

// Démarre la session pour gérer l'authentification et le panier
session_start();

// Charge l'autoloader Composer ou un autoloader personnalisé
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Importe le routeur depuis le noyau
use Mini\Core\Router;

// Définit les routes de l'application
// Format: [méthode HTTP, chemin, [Contrôleur, action]]
$routes = [
    // Page d'accueil
    ['GET', '/', [\Mini\Controllers\HomeController::class, 'index']],
    
    // Routes produits
    ['GET', '/produits', [\Mini\Controllers\ProductController::class, 'list']],
    ['GET', '/produit', [\Mini\Controllers\ProductController::class, 'detail']],
    ['GET', '/categorie', [\Mini\Controllers\ProductController::class, 'category']],
    
    // Routes authentification
    ['GET', '/inscription', [\Mini\Controllers\AuthController::class, 'showRegister']],
    ['POST', '/inscription', [\Mini\Controllers\AuthController::class, 'register']],
    ['GET', '/connexion', [\Mini\Controllers\AuthController::class, 'showLogin']],
    ['POST', '/connexion', [\Mini\Controllers\AuthController::class, 'login']],
    ['GET', '/deconnexion', [\Mini\Controllers\AuthController::class, 'logout']],
    
    // Routes panier
    ['GET', '/panier', [\Mini\Controllers\CartController::class, 'show']],
    ['POST', '/panier/ajouter', [\Mini\Controllers\CartController::class, 'add']],
    ['POST', '/panier/modifier', [\Mini\Controllers\CartController::class, 'update']],
    ['POST', '/panier/supprimer', [\Mini\Controllers\CartController::class, 'remove']],
    
    // Routes commandes
    ['POST', '/commande/valider', [\Mini\Controllers\OrderController::class, 'create']],
    ['GET', '/commandes', [\Mini\Controllers\OrderController::class, 'list']],
    ['GET', '/commande', [\Mini\Controllers\OrderController::class, 'detail']],
];

// Récupère la méthode HTTP de la requête
$method = $_SERVER['REQUEST_METHOD'];

// Récupère l'URI de la requête
$uri = $_SERVER['REQUEST_URI'];

// Crée une instance du routeur avec les routes définies
$router = new Router($routes);

// Distribue la requête vers le bon contrôleur
$router->dispatch($method, $uri);