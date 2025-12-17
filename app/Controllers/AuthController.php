<?php
// Active le mode strict
declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Product;
use Mini\Models\Category;

// Contrôleur de gestion des produits
final class ProductController extends Controller
{
    /**
     * Affiche la liste de tous les produits avec filtres
     */
    public function list(): void
    {
        // Récupère les paramètres de filtrage
        $categoryId = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $minPrice = isset($_GET['min_prix']) ? (float)$_GET['min_prix'] : null;
        $maxPrice = isset($_GET['max_prix']) ? (float)$_GET['max_prix'] : null;
        
        // Récupère les produits filtrés
        $products = Product::getFiltered($categoryId, $search, $minPrice, $maxPrice);
        
        // Récupère toutes les catégories pour les filtres
        $categories = Category::getAll();
        
        // Affiche la vue liste
        $this->render('product/list', [
            'title' => 'Tous nos instruments',
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $categoryId,
            'search' => $search
        ]);
    }
    
    /**
     * Affiche le détail d'un produit
     */
    public function detail(): void
    {
        // Récupère l'ID du produit depuis l'URL
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        // Récupère le produit
        $product = Product::findById($id);
        
        // Si le produit n'existe pas, redirige vers la liste
        if (!$product) {
            header('Location: /produits');
            exit;
        }
        
        // Récupère les produits similaires (même catégorie)
        $similarProducts = Product::getByCategoryId($product['categorie_id'], 4, $id);
        
        // Affiche la vue détail
        $this->render('product/detail', [
            'title' => $product['nom'],
            'product' => $product,
            'similarProducts' => $similarProducts
        ]);
    }
    
    /**
     * Affiche les produits d'une catégorie
     */
    public function category(): void
    {
        // Récupère le slug de la catégorie
        $slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
        
        // Récupère la catégorie
        $category = Category::findBySlug($slug);
        
        // Si la catégorie n'existe pas, redirige
        if (!$category) {
            header('Location: /produits');
            exit;
        }
        
        // Récupère les produits de cette catégorie
        $products = Product::getByCategoryId($category['id']);
        
        // Récupère toutes les catégories
        $categories = Category::getAll();
        
        // Affiche la vue
        $this->render('product/category', [
            'title' => $category['nom'] . ' - Mozikako',
            'category' => $category,
            'products' => $products,
            'categories' => $categories
        ]);
    }
}