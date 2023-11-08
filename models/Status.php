<?php

class Status{

    // nous allons créer les propriétés de l'objet en fonction des champs de la table type, ils seront privés
    private int $_id;
    private string $_name;

    /**
     * Permet de récupérer tous les status de la base de données
     * @return array tableau contenant tous les status
     */
    public static function getAllStatus(): array
    {
        try {
            $pdo = Database::createInstancePDO();
            $sql = 'SELECT * FROM `status`';
            $stmt = $pdo->query($sql); // on exexute la requête à l'aide de la méthode query() de PDO
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // on retourne un tableau associatif
        } catch (PDOException $e) {
            // echo 'Erreur : ' . $e->getMessage();
        }
    }
}
