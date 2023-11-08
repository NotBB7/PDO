<?php

class Type
{

    // nous allons créer les propriétés de l'objet en fonction des champs de la table type, ils seront privés
    private int $_id;
    private string $_name;
    private string $_tva;

    /**
     * Permet de récupérer tous les types de la base de données
     * @return array tableau contenant tous les types
     */
    public static function getAllTypes(): array
    {
        try {
            $pdo = Database::createInstancePDO();
            $sql = 'SELECT * FROM `type`';
            $stmt = $pdo->query($sql); // on exexute la requête à l'aide de la méthode query() de PDO
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // on retourne un tableau associatif
        } catch (PDOException $e) {
            // echo 'Erreur : ' . $e->getMessage();
        }
    }
}
