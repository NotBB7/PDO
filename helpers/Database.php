<?php

class Database
{
    /**
     * Permet de créer une instance de PDO
     * @return object Instance PDO ou false
     */
    public static function createInstancePDO(): object
    {
        try {
            // On se connecte à MySQL à l'aide de PDO
            $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
            // A Activer seulement en developpement
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // On retourne l'instance de PDO
            return $pdo;
        } catch (PDOException $e) {
            // test unitaire pour vérifier que la connexion à la base de données fonctionne
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
}
