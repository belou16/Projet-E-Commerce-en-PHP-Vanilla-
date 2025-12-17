<?php

// Retenir son utilisation  => Database::getPDO()
// Design Pattern : Singleton
/**
 * Classe qui va nous permettre de nous connecter à notre base de données = oshop
 */
namespace Mini\Core;

use PDO;
use Exception;

class Database {
    private static $instance = null;
    private $connection;
    
    /**
     * Constructeur privé pour pattern Singleton
     */
    private function __construct() {
        // Récupération des données du fichier de config
        $configPath = __DIR__ . '/../Views/config.ini';
        
        // Vérifie si le fichier existe
        if (!file_exists($configPath)) {
            die("Erreur : Le fichier config.ini est introuvable. Chemin : " . $configPath);
        }
        
        $config = parse_ini_file($configPath, true);
        
        if (!$config || !isset($config['database'])) {
            die("Erreur : Le fichier config.ini est mal configuré.");
        }
        
        $db = $config['database'];
        
        try {
            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
            $this->connection = new PDO($dsn, $db['username'], $db['password']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo 'Erreur de connexion à la base de données...<br>';
            echo $e->getMessage() . '<br>';
            echo '<pre>';
            echo $e->getTraceAsString();
            echo '</pre>';
            exit;
        }
    }
    
    /**
     * Récupère l'instance unique de la base de données
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Retourne la connexion PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Méthode statique pour récupérer directement PDO (compatible avec votre ancien code)
     */
    public static function getPDO() {
        return self::getInstance()->getConnection();
    }
    
    /**
     * Empêche le clonage de l'instance
     */
    private function __clone() {}
    
    /**
     * Empêche la désérialisation de l'instance
     */
    public function __wakeup() {
        throw new Exception("Impossible de désérialiser un singleton");
    }
}