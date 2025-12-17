<?php
// Active le mode strict
declare(strict_types=1);

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

// Modèle d'utilisateur
class User
{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $role;
    
    // ==================== GETTERS / SETTERS ====================
    
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getNom() { return $this->nom; }
    public function setNom($nom) { $this->nom = $nom; }
    
    public function getPrenom() { return $this->prenom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    
    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }
    
    public function getRole() { return $this->role; }
    public function setRole($role) { $this->role = $role; }
    
    // ==================== MÉTHODES CRUD ====================
    
    /**
     * Récupère un utilisateur par son email
     * @param string $email
     * @return array|null
     */
    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Récupère un utilisateur par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Crée un nouvel utilisateur
     * @return bool
     */
    public function save(): bool
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO users (nom, prenom, email, password, role) 
            VALUES (?, ?, ?, ?, 'client')
        ");
        return $stmt->execute([
            $this->nom, 
            $this->prenom, 
            $this->email, 
            $this->password
        ]);
    }
}