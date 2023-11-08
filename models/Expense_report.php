<?php

class Expense_report
{

    // nous allons créer les propriétés de l'objet en fonction des champs de la table employees, ils seront privés
    private int $_id;
    private string $_date;
    private float $_amount_ttc;
    private float $_amount_ht;
    private string $_description;
    private string $_proof;
    private string $_cancel_reason;
    private string $_decisions_date;
    private int $_id_type;
    private int $_id_statut;
    private int $_id_employee;

    // nous allons utiliser la méthode magique __get pour récupérer les propriétés de l'objet en dehors de la classe
    function __get(string $name)
    {
        return $this->$name;
    }


    /**
     * Permet de rajouter une dépense dans la base de données
     * @param array $post_form tableau contenant les données du formulaire
     * @param string $userFileIn64 chaine de caractères contenant le fichier uploadé en base64
     * @param int $id_employee id de l'employé
     * @return bool true si la dépense a été ajouté, sinon false
     */
    public static function addExpenseReport(array $post_form, string $userFileIn64, int $id_employee): bool
    {
        try {
            // Creation d'une instance de connexion à la base de données
            $pdo = Database::createInstancePDO();

            // requête SQL pour ajouter une note de frais avec des marqueurs nominatifs pour faciliter le bindValue
            $sql = 'INSERT INTO `expense_report` (`exp_date`, `exp_amount_ttc`, `exp_amount_ht`, `exp_description`, `exp_proof`, `typ_id`, `emp_id`)
            VALUES (:date, :amount_ttc, :amount_ht, :description, :proof, :id_type, :id_employee)';

            // On prépare la requête avant de l'exécuter
            $stmt = $pdo->prepare($sql);

            // On injecte les valeurs dans la requête et nous utilisons la méthode bindValue pour se prémunir des injections SQL
            // Nous utilisons également la méthode PDO::PARAM_STR pour préciser que le paramètre est une chaîne de caractères
            // Nous utilisons htmlspecialchars pour se prémunir des failles XSS

            $stmt->bindValue(':date', htmlspecialchars($post_form['date']), PDO::PARAM_STR);
            $stmt->bindValue(':amount_ttc', htmlspecialchars($post_form['amount']), PDO::PARAM_STR);

            // On calcule le montant HT
            $amount_ht = $post_form['type'] == 4 || $post_form['type'] == 5 ? $post_form['amount'] * 0.9 : $post_form['amount'] * 0.8;
            $stmt->bindValue(':amount_ht', $amount_ht, PDO::PARAM_STR);
            $stmt->bindValue(':description', htmlspecialchars($post_form['description']), PDO::PARAM_STR);
            $stmt->bindValue(':proof', $userFileIn64, PDO::PARAM_STR);
            $stmt->bindValue(':id_type', htmlspecialchars($post_form['type']), PDO::PARAM_STR);
            $stmt->bindValue(':id_employee', $id_employee, PDO::PARAM_STR);

            // On exécute la requête, elle sera true si elle réussi, dans le cas contraire il y aura une exception
            return $stmt->execute();
        } catch (PDOException $e) {
            // test unitaire pour vérifier que la dépense n'a pas été ajouté et connaitre la raison
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Permet de modifier une dépense de la base de données
     * @param array $post_form tableau contenant les données du formulaire
     * @param string $userFileIn64 chaine de caractères contenant le fichier uploadé en base64
     * @param int $id_employee id de l'employé
     * @return bool true si la dépense a été modifié, sinon false
     */
    public static function updateExpenseReport(array $post_form, string $userFileIn64, int $id_employee, int $id_expense): bool
    {
        try {
            // Creation d'une instance de connexion à la base de données
            $pdo = Database::createInstancePDO();

            // requête SQL pour modifier une note de frais avec des marqueurs nominatifs pour faciliter le bindValue
            // avec une clause where pour ne modifier que celle qui a l'id correspondant
            $sql = 'UPDATE `expense_report` 
            SET `exp_date` = :date, 
            `exp_amount_ttc` = :amount_ttc, 
            `exp_amount_ht`= :amount_ht, 
            `exp_description` = :description, 
            `exp_proof` = :proof, 
            `typ_id`= :id_type, 
            `emp_id` = :id_employee 
            WHERE `exp_id` = :id_expense_report';

            // On prépare la requête avant de l'exécuter
            $stmt = $pdo->prepare($sql);

            // On injecte les valeurs dans la requête et nous utilisons la méthode bindValue pour se prémunir des injections SQL
            // Nous utilisons également la méthode PDO::PARAM_STR pour préciser que le paramètre est une chaîne de caractères
            // Nous utilisons htmlspecialchars pour se prémunir des failles XSS

            $stmt->bindValue(':date', htmlspecialchars($post_form['date']), PDO::PARAM_STR);
            $stmt->bindValue(':amount_ttc', htmlspecialchars($post_form['amount']), PDO::PARAM_STR);

            // On calcule le montant HT
            $amount_ht = $post_form['type'] == 4 || $post_form['type'] == 5 ? $post_form['amount'] * 0.9 : $post_form['amount'] * 0.8;
            $stmt->bindValue(':amount_ht', $amount_ht, PDO::PARAM_STR);
            $stmt->bindValue(':description', htmlspecialchars($post_form['description']), PDO::PARAM_STR);
            $stmt->bindValue(':proof', $userFileIn64, PDO::PARAM_STR);
            $stmt->bindValue(':id_type', htmlspecialchars($post_form['type']), PDO::PARAM_STR);
            $stmt->bindValue(':id_employee', $id_employee, PDO::PARAM_STR);
            $stmt->bindValue(':id_expense_report', $id_expense, PDO::PARAM_STR);

            // On exécute la requête, elle sera true si elle réussi, dans le cas contraire il y aura une exception
            return $stmt->execute();
        } catch (PDOException $e) {
            // test unitaire pour vérifier que la dépense n'a pas été ajouté et connaitre la raison
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Permet de récupérer toutes les dépenses de la base de données
     * @param int $id_employee id de l'employé
     * @return array tableau contenant toutes les dépenses
     */
    public static function getAllExpenseReports(int $id_employee = NULL): array
    {
        try {
            $pdo = Database::createInstancePDO();
            // nous allons créer une requête SQL conditionnelle en fonction de la valeur de $id_employee
            // si l'id de l'employé est différent de NULL, nous allons récupérer toutes les dépenses de l'employé
            $sql = $id_employee != NULL ? 'SELECT * FROM `expense_report` NATURAL JOIN `STATUS` NATURAL JOIN `TYPE` WHERE `emp_id` = :id_employee ORDER BY `exp_date` DESC' : 'SELECT * FROM `expense_report` NATURAL JOIN `STATUS` NATURAL JOIN `TYPE`';
            $stmt = $pdo->prepare($sql); // on prépare la requête avant de l'exécuter

            // nous allons injecter la valeur de $id_employee dans la requête si elle est différente de NULL
            if ($id_employee != NULL) {
                $stmt->bindValue(':id_employee', $id_employee, PDO::PARAM_INT); // on injecte la valeur de $id_employee dans la requête
            }

            // si la requête s'exécute, on retourne un tableau associatif
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // on retourne un tableau associatif
            } else {
                // sinon on retourne un tableau vide
                return [];
            }
        } catch (PDOException $e) {
            // echo 'Erreur : ' . $e->getMessage();
            return [];
        }
    }

    /**
     * Permet les infos d'une dépense de la base de données
     * @param int $id_expense id de la dépense
     * @return array tableau contenant toutes les infos de la dépense choisie
     */
    public static function getExpense(int $id_expense): array
    {
        try {
            $pdo = Database::createInstancePDO();
            // nous allons créer une requête SQL conditionnelle en fonction de la valeur de $id_expense
            $sql = 'SELECT * FROM `expense_report` NATURAL JOIN `STATUS` NATURAL JOIN `TYPE` WHERE `exp_id` = :id_expense';
            $stmt = $pdo->prepare($sql); // on prépare la requête avant de l'exécuter
            $stmt->bindValue(':id_expense', $id_expense, PDO::PARAM_INT); // on injecte l'ID de la dépense dans la requête

            // si la requête s'exécute, on retourne un tableau associatif contenant les données de la dépense
            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC); // Nous récupérons un tableau associatif
                if (!empty($result)) {
                    return $result;
                } else {
                    return [];
                }
            } else {
                // sinon on retourne un tableau vide
                return [];
            }
        } catch (PDOException $e) {
            // echo 'Erreur : ' . $e->getMessage();
            return []; // on retourne égalament un tableau vide
        }
    }

    /**
     * Permet de supprimer une dépense de la base de données
     * @param int $id_expense id de la dépense
     * @return bool true si la dépense a été supprimé, sinon false
     */
    public static function deleteExpense(int $id_expense): bool
    {
        try {
            $pdo = Database::createInstancePDO();
            // nous allons créer une requête SQL conditionnelle en fonction de la valeur de $id_expense
            $sql = 'DELETE FROM `expense_report` WHERE `exp_id` = :id_expense';
            $stmt = $pdo->prepare($sql); // on prépare la requête avant de l'exécuter
            $stmt->bindValue(':id_expense', $id_expense, PDO::PARAM_INT); // on injecte la valeur de $id_employee dans la requête

            // si la requête s'exécute, on retourne true, sinon false
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // echo 'Erreur : ' . $e->getMessage();
            return false; // on retourne égalament false
        }
    }
}
