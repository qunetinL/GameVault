<?php

namespace App\Helpers;

/**
 * DbHelper - Classe utilitaire pour la connexion à la base de données via PDO
 */
class DbHelper
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $dbConfig = $config['db'];

        $host = $dbConfig['host'];
        $dbname = $dbConfig['name'];
        $user = $dbConfig['user'];
        $pass = $dbConfig['pass'];

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];

        try {
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            // Si la base n'existe pas, tenter de se connecter à MySQL sans dbname (si possible)
            if ($e->getCode() == 1049) {
                try {
                    // Connexion sans dbname pour diagnostiquer ou suggérer l'initialisation
                    $dsnBase = "mysql:host=$host;charset=utf8mb4";
                    $tempPdo = new \PDO($dsnBase, $user, $pass, $options);

                    // On pourrait tenter un CREATE DATABASE ici si l'utilisateur a les droits,
                    // mais pour l'instant on se contente de l'erreur explicite.
                    throw new \Exception("La base de données '$dbname' est manquante. Veuillez vérifier que le script schema.sql a été exécuté ou que Docker a correctement initialisé le volume.", 1049);
                } catch (\PDOException $innerE) {
                    throw new \PDOException("Impossible de se connecter au serveur MySQL : " . $innerE->getMessage(), (int) $innerE->getCode());
                }
            }
            throw new \Exception("Erreur de connexion : " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DbHelper();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
