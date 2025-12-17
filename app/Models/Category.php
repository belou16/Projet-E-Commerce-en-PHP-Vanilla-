<?php
// Active le mode strict
declare(strict_types=1);

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

// Modèle de catégorie
class Category
{
    private $id;
    private $nom;
    private $slug;
    private $description;
    private $image_url;
    
    // ==================== GETTERS / SETTERS ====================
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getNom() { return $this->nom; }
    public function setNom($nom) { $this->nom = $nom; }
    
    public function getSlug() { return $this->slug; }
    public function setSlug($slug) { $this->slug = $slug; }
    
    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }
    
    public function getImageUrl() { return $this->image_url; }
    public function setImageUrl($image_url) { $this->image_url = $image_url; }
    
    // ==================== MÉTHODES CRUD ====================
    
    /**
     * Récupère toutes les catégories
     * @return array
     */
    public static function getAll(): array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère une catégorie par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Récupère une catégorie par son slug
     * @param string $slug
     * @return array|null
     */
    public static function findBySlug(string $slug): ?array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}