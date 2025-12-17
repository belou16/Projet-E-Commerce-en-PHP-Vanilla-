<?php
// Active le mode strict
declare(strict_types=1);

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

// Modèle de commande
class Order
{
    /**
     * Crée une nouvelle commande avec ses produits
     * @param int $userId
     * @param float $montantTotal
     * @param array $items
     * @return int|false ID de la commande créée ou false en cas d'erreur
     */
    public static function create(int $userId, float $montantTotal, array $items)
    {
        $pdo = Database::getPDO();
        
        try {
            // Démarre une transaction
            $pdo->beginTransaction();
            
            // Crée la commande
            $stmt = $pdo->prepare("
                INSERT INTO commandes (user_id, montant_total, statut) 
                VALUES (?, ?, 'en_attente')
            ");
            $stmt->execute([$userId, $montantTotal]);
            $orderId = (int)$pdo->lastInsertId();
            
            // Ajoute chaque produit à la commande
            $stmt = $pdo->prepare("
                INSERT INTO commande_produits (commande_id, produit_id, quantite, prix_unitaire) 
                VALUES (?, ?, ?, ?)
            ");
            
            foreach ($items as $item) {
                $stmt->execute([
                    $orderId,
                    $item['produit_id'],
                    $item['quantite'],
                    $item['prix']
                ]);
                
                // Diminue le stock
                Product::decreaseStock($item['produit_id'], $item['quantite']);
            }
            
            // Valide la transaction
            $pdo->commit();
            
            return $orderId;
            
        } catch (\Exception $e) {
            // Annule la transaction en cas d'erreur
            $pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Récupère les commandes d'un utilisateur
     * @param int $userId
     * @return array
     */
    public static function getByUserId(int $userId): array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT 
                c.*,
                COUNT(cp.id) as nb_produits
            FROM commandes c
            LEFT JOIN commande_produits cp ON c.id = cp.commande_id
            WHERE c.user_id = ?
            GROUP BY c.id
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère une commande par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Récupère les produits d'une commande
     * @param int $orderId
     * @return array
     */
    public static function getItems(int $orderId): array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT 
                cp.*,
                p.nom,
                p.image_url,
                c.nom as categorie_nom
            FROM commande_produits cp
            INNER JOIN produits p ON cp.produit_id = p.id
            LEFT JOIN categories c ON p.categorie_id = c.id
            WHERE cp.commande_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}