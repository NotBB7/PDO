<?php

class Employees
{

    // nous allons créer les propriétés de l'objet en fonction des champs de la table employees, ils seront privés
    private int $_id;
    private string $_lastname;
    private string $_firstname;
    private string $_phoneNumber;
    private string $_mail;
    private string $_password;

    // nous allons utiliser la méthode magique __get pour récupérer les propriétés de l'objet en dehors de la classe
    function __get(string $name)
    {
        return $this->$name;
    }

    // nous allons utiliser le principe de l'hydratation pour remplir les propriétés de l'objet lors de son instanciation
    // nous utilisons le constructeur pour cela : __construct
    function __construct(string $mail)
    {
        $this->getEmployeeByMail($mail);
    }

    /**
     * Permet de rajouter un employé dans la base de données
     * @param array $post_form tableau contenant les données du formulaire
     * @return bool true si l'employé a été ajouté, sinon false
     */
    public static function addEmployee(array $post_form): bool
    {
        try {
            // Creation d'une instance de connexion à la base de données
            $pdo = Database::createInstancePDO();

            // requête SQL pour ajouter un employé avec des marqueurs nominatifs pour faciliter le bindValue
            $sql = 'INSERT INTO `employees` (`emp_lastname`, `emp_firstname`, `emp_phonenumber`, `emp_mail`, `emp_password`)
            VALUES (:lastname, :firstname, :phonenumber, :mail, :password)';

            // On prépare la requête avant de l'exécuter
            $stmt = $pdo->prepare($sql);

            // On injecte les valeurs dans la requête et nous utilisons la méthode bindValue pour se prémunir des injections SQL
            // bien penser à hasher le mot de passe
            $stmt->bindValue(':password', password_hash($post_form['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);

            $stmt->bindValue(':firstname', htmlspecialchars($post_form['firstname']), PDO::PARAM_STR);
            $stmt->bindValue(':phonenumber', htmlspecialchars($post_form['phoneNumber']), PDO::PARAM_STR);
            $stmt->bindValue(':lastname', htmlspecialchars($post_form['lastname']), PDO::PARAM_STR);
            $stmt->bindValue(':mail', htmlspecialchars($post_form['mail']), PDO::PARAM_STR);

            // On exécute la requête, elle sera true si elle réussi, dans le cas contraire il y aura une exception
            return $stmt->execute();
        } catch (PDOException $e) {
            // test unitaire pour vérifier que l'employé n'a pas été ajouté et connaitre la raison
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Permet de vérifier si le mail est déja présent dans la base de données pour éviter doublon
     * @param string $mail le mail à vérifier
     * @return bool true si le mail existe, sinon false
     */
    public static function checkIfMailExist(string $mail): bool
    {
        try {
            $pdo = Database::createInstancePDO();
            $sql = 'SELECT COUNT(*) FROM `employees` WHERE `emp_mail` = :mail'; // marqueur nominatif
            $stmt = $pdo->prepare($sql); // on prepare la requete pour se prémunir des injections SQL
            $stmt->bindValue(':mail', htmlspecialchars($mail), PDO::PARAM_STR); // on associe le marqueur nominatif à la variable $login
            $stmt->execute(); // on execute la requête

            // A l'aide d'une ternaire, nous vérifions si nous avons un résultat à l'aide de la méthode fetchColumn()
            // Si le résultat est supérieur à 0, nous retournons true, sinon nous retournons false
            $stmt->fetchColumn() > 0 ? $result = true : $result = false;

            // nous retournons le result
            return $result;
        } catch (PDOException $e) {
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Permet de récupérer un employé en fonction de son mail
     * @param string $mail le mail de l'employé
     * @return void
     */
    private function getEmployeeByMail(string $mail): void
    {
        try {
            $pdo = Database::createInstancePDO();
            $sql = 'SELECT * FROM `employees` WHERE `emp_mail` = :mail'; // marqueur nominatif
            $stmt = $pdo->prepare($sql); // on prepare la requete
            $stmt->bindValue(':mail', htmlspecialchars($mail), PDO::PARAM_STR); // on associe le marqueur nominatif à la variable $login
            $stmt->execute(); // on execute la requête

            // Je fais fetch() pour récupérer un tableau associatif avec les clés qui sont les noms des colonnes SQL
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // hydratation de l'objet
            $this->_id = $result['emp_id'];
            $this->_lastname = $result['emp_lastname'];
            $this->_firstname = $result['emp_firstname'];
            $this->_phoneNumber = $result['emp_phonenumber'];
            $this->_mail = $result['emp_mail'];
            $this->_password = $result['emp_password'];
        } catch (PDOException $e) {
            // echo 'Erreur : ' . $e->getMessage();
        }
    }
}
