<?php

// Ici je définit le namespace ou il y aura ma class
namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class Product
{
    private $id;
    private $nom;
    private $description;
    private $prix;
    private $stock;
    private $image_url;

    // =====================
    // Getters / Setters
    // =====================

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    // =====================
    // Méthodes CRUD
    // =====================

    /**
     * Récupère tous les produits
     * @return array
     */
    public static function getAll()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM produit ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un produit par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM produit WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouveau produit
     * @return bool
     */
    public function save()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("INSERT INTO produit (nom, description, prix, stock, image_url) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $this->nom,
            $this->description,
            $this->prix,
            $this->stock,
            $this->image_url
        ]);
    }

    /**
     * Met à jour les informations d'un produit existant
     * @return bool
     */
    public function update()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE produit SET nom = ?, description = ?, prix = ?, stock = ?, image_url = ? WHERE id = ?");
        return $stmt->execute([
            $this->nom,
            $this->description,
            $this->prix,
            $this->stock,
            $this->image_url,
            $this->id
        ]);
    }

    /**
     * Supprime un produit
     * @return bool
     */
    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM produit WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}

