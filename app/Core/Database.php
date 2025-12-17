<?php

namespace Mini\Core;

use PDO;

class Database
{
    private $dbh;
    private static $_instance;

    private function __construct()
    {
        $configPath = dirname(__DIR__, 2) . '/public/config.ini';

        if (!file_exists($configPath)) {
            throw new \Exception("config.ini introuvable : $configPath");
        }

        $configData = parse_ini_file($configPath);

        if ($configData === false) {
            throw new \Exception("Impossible de parser config.ini");
        }

        $this->dbh = new PDO(
            "mysql:host={$configData['DB_HOST']};dbname={$configData['DB_NAME']};charset=utf8",
            $configData['DB_USERNAME'],
            $configData['DB_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]
        );
    }

    public static function getPDO()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new Database();
        }
        return self::$_instance->dbh;
    }
}
