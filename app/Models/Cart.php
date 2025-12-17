<?php
// Active le mode strict
declare(strict_types=1);

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

// Modèle du panier
class Cart
{
    /**
     * Récupère tous les articles du panier d'un utilisateur
     * @param int $userId
     * @return array
     */
    public static function getByUserId(int $userId): array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT 
                pa.id as panier_id,
                pa.quantite,
                p.id as produit_id,
                p.nom,
                p.prix,
                p.stock,
                p.image_url,
                c.nom as categorie_nom
            FROM paniers pa
            INNER JOIN produits p ON pa.produit_id = p.id
            LEFT JOIN categories c ON p.categorie_id = c.id
            WHERE pa.user_id = ?
            ORDER BY pa.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Ajoute un produit au panier ou met à jour la quantité
     * @param int $userId
     * @param int $productId
     * @param int $quantite
     * @return bool
     */
    public static function addOrUpdate(int $userId, int $productId, int $quantite): bool
    {
        $pdo = Database::getPDO();
        
        // Vérifie si le produit est déjà dans le panier
        $stmt = $pdo->prepare("SELECT quantite FROM paniers WHERE user_id = ? AND produit_id = ?");
        $stmt->execute([$userId, $productId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            // Met à jour la quantité
            $newQuantite = $existing['quantite'] + $quantite;
            $stmt = $pdo->prepare("UPDATE paniers SET quantite = ? WHERE user_id = ? AND produit_id = ?");
            return $stmt->execute([$newQuantite, $userId, $productId]);
        } else {
            // Ajoute au panier
            $stmt = $pdo->prepare("INSERT INTO paniers (user_id, produit_id, quantite) VALUES (?, ?, ?)");
            return $stmt->execute([$userId, $productId, $quantite]);
        }
    }
    
    /**
     * Met à jour la quantité d'un produit dans le panier
     * @param int $userId
     * @param int $productId
     * @param int $quantite
     * @return bool
     */
    public static function updateQuantity(int $userId, int $productId, int $quantite): bool
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE paniers SET quantite = ? WHERE user_id = ? AND produit_id = ?");
        return $stmt->execute([$quantite, $userId, $productId]);
    }
    
    /**
     * Supprime un produit du panier
     * @param int $userId
     * @param int $productId
     * @return bool
     */
    public static function remove(int $userId, int $productId): bool
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM paniers WHERE user_id = ? AND produit_id = ?");
        return $stmt->execute([$userId, $productId]);
    }
    
    /**
     * Vide le panier d'un utilisateur
     * @param int $userId
     * @return bool
     */
    public static function clearByUserId(int $userId): bool
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM paniers WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
    
    /**
     * Compte le nombre d'articles dans le panier
     * @param int $userId
     * @return int
     */
    public static function countItems(int $userId): int
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT SUM(quantite) as total FROM paniers WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }
}