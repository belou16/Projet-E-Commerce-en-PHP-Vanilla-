<?php
// Active le mode strict pour la vérification des types
declare(strict_types=1);

// Déclare l'espace de noms pour ce contrôleur
namespace Mini\Controllers;

// Importe les classes nécessaires
use Mini\Core\Controller;
use Mini\Models\Product;
use Mini\Models\Category;

// Contrôleur de la page d'accueil
final class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil avec les produits vedettes
     */
    public function index(): void
    {
        // Récupère les 8 derniers produits ajoutés
        $featuredProducts = Product::getLatest(8);
        
        // Récupère toutes les catégories pour la navigation
        $categories = Category::getAll();
        
        // Affiche la vue d'accueil
        $this->render('home/index', [
            'title' => 'Mozikako - Instruments de Musique',
            'featuredProducts' => $featuredProducts,
            'categories' => $categories
        ]);
    }
}