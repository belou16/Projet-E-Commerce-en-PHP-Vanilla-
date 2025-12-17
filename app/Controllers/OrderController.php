<?php
// Active le mode strict
declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Order;
use Mini\Models\Cart;
use Mini\Models\Product;

// Contrôleur de gestion des commandes
final class OrderController extends Controller
{
    /**
     * Crée une nouvelle commande à partir du panier
     */
    public function create(): void
    {
        // Vérifie POST et connexion
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header('Location: /panier');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Récupère le panier
        $items = Cart::getByUserId($userId);
        
        // Vérifie que le panier n'est pas vide
        if (empty($items)) {
            $_SESSION['cart_error'] = 'Votre panier est vide';
            header('Location: /panier');
            exit;
        }
        
        // Vérifie le stock pour chaque produit
        foreach ($items as $item) {
            $product = Product::findById($item['produit_id']);
            if (!$product || $product['stock'] < $item['quantite']) {
                $_SESSION['cart_error'] = 'Stock insuffisant pour ' . $item['nom'];
                header('Location: /panier');
                exit;
            }
        }
        
        // Calcule le montant total
        $montantTotal = 0;
        foreach ($items as $item) {
            $montantTotal += $item['prix'] * $item['quantite'];
        }
        
        // Crée la commande
        $orderId = Order::create($userId, $montantTotal, $items);
        
        if ($orderId) {
            // Vide le panier
            Cart::clearByUserId($userId);
            
            // Message de succès
            $_SESSION['order_success'] = 'Commande validée avec succès !';
            
            // Redirige vers le détail de la commande
            header('Location: /commande?id=' . $orderId);
            exit;
        } else {
            $_SESSION['cart_error'] = 'Erreur lors de la création de la commande';
            header('Location: /panier');
            exit;
        }
    }
    
    /**
     * Affiche la liste des commandes de l'utilisateur
     */
    public function list(): void
    {
        // Vérifie la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: /connexion');
            exit;
        }
        
        // Récupère les commandes
        $orders = Order::getByUserId($_SESSION['user_id']);
        
        $this->render('order/list', [
            'title' => 'Mes commandes - Mozikako',
            'orders' => $orders
        ]);
    }
    
    /**
     * Affiche le détail d'une commande
     */
    public function detail(): void
    {
        // Vérifie la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: /connexion');
            exit;
        }
        
        // Récupère l'ID de la commande
        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        // Récupère la commande
        $order = Order::findById($orderId);
        
        // Vérifie que la commande existe et appartient à l'utilisateur
        if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
            header('Location: /commandes');
            exit;
        }
        
        // Récupère les produits de la commande
        $items = Order::getItems($orderId);
        
        $this->render('order/detail', [
            'title' => 'Commande #' . $orderId . ' - Mozikako',
            'order' => $order,
            'items' => $items
        ]);
    }
}