<?php

/**
 * DbHelper - Classe utilitaire pour la connexion à la base de données via PDO
 */
class DbHelper
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        // Paramètres de connexion (Docker-compose)
        $host = 'db';
        $dbName = 'gamevault';
        $user = 'gamer';
        $pass = 'password';
        $charset = 'utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];

        try {
            // Tenter la connexion normale
            $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            // Si la base n'existe pas, tenter de se connecter à MySQL sans dbname (si possible)
            if ($e->getCode() == 1049) {
                try {
                    // Connexion sans dbname pour diagnostiquer ou suggérer l'initialisation
                    $dsnBase = "mysql:host=$host;charset=$charset";
                    $tempPdo = new PDO($dsnBase, $user, $pass, $options);

                    // On pourrait tenter un CREATE DATABASE ici si l'utilisateur a les droits,
                    // mais il vaut mieux informer que le script schema.sql doit être lancé.
                    throw new \Exception("La base de données '$dbName' est manquante. Veuillez vérifier que le script schema.sql a été exécuté ou que Docker a correctement initialisé le volume.", 1049);
                } catch (\PDOException $innerE) {
                    throw new \PDOException("Impossible de se connecter au serveur MySQL : " . $innerE->getMessage(), (int) $innerE->getCode());
                }
            }
            throw $e;
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
