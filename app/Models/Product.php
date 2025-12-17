<?php
// Active le mode strict
declare(strict_types=1);

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

// Modèle de produit
class Product
{
    private $id;
    private $nom;
    private $description;
    private $prix;
    private $stock;
    private $categorie_id;
    private $image_url;
    
    // ==================== GETTERS / SETTERS ====================
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getNom() { return $this->nom; }
    public function setNom($nom) { $this->nom = $nom; }
    
    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }
    
    public function getPrix() { return $this->prix; }
    public function setPrix($prix) { $this->prix = $prix; }
    
    public function getStock() { return $this->stock; }
    public function setStock($stock) { $this->stock = $stock; }
    
    public function getCategorieId() { return $this->categorie_id; }
    public function setCategorieId($categorie_id) { $this->categorie_id = $categorie_id; }
    
    public function getImageUrl() { return $this->image_url; }
    public function setImageUrl($image_url) { $this->image_url = $image_url; }
    
    // ==================== MÉTHODES CRUD ====================
    
    /**
     * Récupère tous les produits
     * @return array
     */
    public static function getAll(): array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("
            SELECT p.*, c.nom as categorie_nom 
            FROM produits p 
            LEFT JOIN categories c ON p.categorie_id = c.id 
            ORDER BY p.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère les derniers produits ajoutés
     * @param int $limit
     * @return array
     */
    public static function getLatest(int $limit = 8): array
    {
        $pdo = Database::getPDO();
        
        $limit = (int)$limit;
        
        $stmt = $pdo->prepare("
            SELECT p.*, c.nom as categorie_nom 
            FROM produits p 
            LEFT JOIN categories c ON p.categorie_id = c.id 
            ORDER BY p.created_at DESC 
            LIMIT $limit
        ");
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    /**
     * Récupère un produit par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT p.*, c.nom as categorie_nom, c.slug as categorie_slug 
            FROM produits p 
            LEFT JOIN categories c ON p.categorie_id = c.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Récupère les produits d'une catégorie
     * @param int $categorieId
     * @param int|null $limit
     * @param int|null $excludeId
     * @return array
     */
    public static function getByCategoryId(int $categorieId, ?int $limit = null, ?int $excludeId = null): array
    {
        $pdo = Database::getPDO();
        
        $sql = "
            SELECT p.*, c.nom as categorie_nom 
            FROM produits p 
            LEFT JOIN categories c ON p.categorie_id = c.id 
            WHERE p.categorie_id = ?
        ";
        
        $params = [$categorieId];
        
        // Exclut un produit spécifique (pour les produits similaires)
        if ($excludeId !== null) {
            $sql .= " AND p.id != ?";
            $params[] = $excludeId;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        // Limite le nombre de résultats
        if ($limit !== null) {
            $sql .= " LIMIT ?";
            $params[] = $limit;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère les produits avec filtres
     * @param int|null $categorieId
     * @param string|null $search
     * @param float|null $minPrice
     * @param float|null $maxPrice
     * @return array
     */
    public static function getFiltered(?int $categorieId = null, ?string $search = null, ?float $minPrice = null, ?float $maxPrice = null): array
    {
        $pdo = Database::getPDO();
        
        $sql = "
            SELECT p.*, c.nom as categorie_nom 
            FROM produits p 
            LEFT JOIN categories c ON p.categorie_id = c.id 
            WHERE 1=1
        ";
        
        $params = [];
        
        // Filtre par catégorie
        if ($categorieId !== null) {
            $sql .= " AND p.categorie_id = ?";
            $params[] = $categorieId;
        }
        
        // Recherche par nom ou description
        if ($search !== null && $search !== '') {
            $sql .= " AND (p.nom LIKE ? OR p.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        // Filtre par prix minimum
        if ($minPrice !== null) {
            $sql .= " AND p.prix >= ?";
            $params[] = $minPrice;
        }
        
        // Filtre par prix maximum
        if ($maxPrice !== null) {
            $sql .= " AND p.prix <= ?";
            $params[] = $maxPrice;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Diminue le stock d'un produit
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public static function decreaseStock(int $id, int $quantity): bool
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE produits SET stock = stock - ? WHERE id = ? AND stock >= ?");
        return $stmt->execute([$quantity, $id, $quantity]);
    }
}