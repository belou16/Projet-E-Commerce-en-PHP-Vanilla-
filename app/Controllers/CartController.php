<?php
// Active le mode strict
declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Cart;
use Mini\Models\Product;

// Contrôleur de gestion du panier
final class CartController extends Controller
{
    /**
     * Affiche le panier
     */
    public function show(): void
    {
        // Si non connecté, affiche le panier vide
        if (!isset($_SESSION['user_id'])) {
            $this->render('cart/show', [
                'title' => 'Mon panier - Mozikako',
                'items' => [],
                'total' => 0
            ]);
            return;
        }
        
        // Récupère les articles du panier
        $items = Cart::getByUserId($_SESSION['user_id']);
        
        // Calcule le total
        $total = 0;
        foreach ($items as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        
        $this->render('cart/show', [
            'title' => 'Mon panier - Mozikako',
            'items' => $items,
            'total' => $total
        ]);
    }
    
    /**
     * Ajoute un produit au panier
     */
    public function add(): void
    {
        // Vérifie que c'est POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /produits');
            exit;
        }
        
        // Vérifie si connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /connexion');
            exit;
        }
        
        // Récupère les données
        $productId = isset($_POST['produit_id']) ? (int)$_POST['produit_id'] : 0;
        $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 1;
        
        // Valide la quantité
        if ($quantite < 1) $quantite = 1;
        
        // Vérifie que le produit existe
        $product = Product::findById($productId);
        if (!$product) {
            header('Location: /produits');
            exit;
        }
        
        // Vérifie le stock
        if ($product['stock'] < $quantite) {
            // Redirige avec erreur
            $_SESSION['cart_error'] = 'Stock insuffisant';
            header('Location: /produit?id=' . $productId);
            exit;
        }
        
        // Ajoute au panier
        Cart::addOrUpdate($_SESSION['user_id'], $productId, $quantite);
        
        // Message de succès
        $_SESSION['cart_success'] = 'Produit ajouté au panier';
        
        // Redirige vers le panier
        header('Location: /panier');
        exit;
    }
    
    /**
     * Modifie la quantité d'un produit dans le panier
     */
    public function update(): void
    {
        // Vérifie POST et connexion
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header('Location: /panier');
            exit;
        }
        
        $productId = isset($_POST['produit_id']) ? (int)$_POST['produit_id'] : 0;
        $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 1;
        
        // Valide la quantité
        if ($quantite < 1) $quantite = 1;
        
        // Vérifie le stock
        $product = Product::findById($productId);
        if ($product && $product['stock'] >= $quantite) {
            Cart::updateQuantity($_SESSION['user_id'], $productId, $quantite);
        }
        
        header('Location: /panier');
        exit;
    }
    
    /**
     * Supprime un produit du panier
     */
    public function remove(): void
    {
        // Vérifie POST et connexion
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header('Location: /panier');
            exit;
        }
        
        $productId = isset($_POST['produit_id']) ? (int)$_POST['produit_id'] : 0;
        
        // Supprime du panier
        Cart::remove($_SESSION['user_id'], $productId);
        
        header('Location: /panier');
        exit;
    }
}