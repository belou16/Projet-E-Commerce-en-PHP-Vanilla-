<?php
// Active le mode strict pour la vérification des types
declare(strict_types=1);

// Démarre la session pour gérer l'authentification et le panier
session_start();

// Charge l'autoloader Composer ou un autoloader personnalisé
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Importe le routeur depuis le noyau
use Mini\Core\Router;
use Mini\Controllers\HomeController;
use Mini\Controllers\ProductController;
use Mini\Controllers\CartController;
use Mini\Controllers\OrderController;

// Définit les routes de l'application
// Format: [méthode HTTP, chemin, [Contrôleur, action]]
$routes = [
    // Page d'accueil
    ['GET', '/', [HomeController::class, 'index']],
    
    // Routes produits
    ['GET', '/produits', [ProductController::class, 'list']],
    ['GET', '/produit', [ProductController::class, 'detail']],
    ['GET', '/categorie', [ProductController::class, 'category']],
    
    // Routes authentification
    ['GET', '/inscription', ['Mini\Controllers\AuthController', 'showRegister']],
    ['POST', '/inscription', ['Mini\Controllers\AuthController', 'register']],
    ['GET', '/connexion', ['Mini\Controllers\AuthController', 'showLogin']],
    ['POST', '/connexion', ['Mini\Controllers\AuthController', 'login']],
    ['GET', '/deconnexion', ['Mini\Controllers\AuthController', 'logout']],
    
    // Routes panier
    ['GET', '/panier', [CartController::class, 'show']],
    ['POST', '/panier/ajouter', [CartController::class, 'add']],
    ['POST', '/panier/modifier', [CartController::class, 'update']],
    ['POST', '/panier/supprimer', [CartController::class, 'remove']],
    
    // Routes commandes
    ['POST', '/commande/valider', [OrderController::class, 'create']],
    ['GET', '/commandes', [OrderController::class, 'list']],
    ['GET', '/commande', [OrderController::class, 'detail']],
];

// Récupère la méthode HTTP de la requête
$method = $_SERVER['REQUEST_METHOD'];

// Récupère l'URI de la requête
$uri = $_SERVER['REQUEST_URI'];

// Crée une instance du routeur avec les routes définies
$router = new Router($routes);

// Distribue la requête vers le bon contrôleur
$router->dispatch($method, $uri);