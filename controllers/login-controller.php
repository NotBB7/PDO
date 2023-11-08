<?php
// nous ouvrons une session
session_start();

// nous vérifions si l'utilisateur est connecté à l'aide de la variable de session user
// si l'utilisateur n'est pas connecté, nous le redirigeons vers la page de connexion
if (isset($_SESSION['user'])) {
    header('Location: ../controllers/home-controller.php');
    exit();
}

require_once '../config.php';
require_once '../helpers/Database.php';

require_once '../models/Employees.php';

// Nous définissons un tableau d'erreurs
$errors = [];

// Déclenchement des actions uniquement à l'aide d'un POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Contrôle du mail : vide
    if (isset($_POST['mail'])) {
        if (empty($_POST['mail'])) {
            $errors['mail'] = 'L\'identifiant est obligatoire';
        }
    }

    // Contrôle du password : vide
    if (isset($_POST['password'])) {
        if (empty($_POST['password'])) {
            $errors['password'] = 'Le mdp est obligatoire';
        }
    }

    if (empty($errors)) {
        if (!Employees::checkIfMailExist($_POST['mail'])) {
            $errors['signIn'] = 'Identifiant incorrect';
        } else {
            // on instancie un obj employé avec l'adresse mail
            $employee = new Employees($_POST['mail']);

            // nous vérifions que le mot de passe correspond à celui en base à l'aide de la fonction password_verify
            // nous récupérons le mot de passe en base à l'aide de notre objet employee
            if (!password_verify($_POST['password'], $employee->_password)) {
                $errors['signIn'] = 'Mot de passe incorrect';
            } else {
                // nous créons une variable de session 'user' avec les informations de l'employé si le mot de passe est correct
                // nous utilisons un tableau associatif avec les clés id, mail, lastname, firstname, phoneNumber
                $_SESSION['user'] = [
                    'id' => $employee->_id,
                    'mail' => $employee->_mail,
                    'lastname' => $employee->_lastname,
                    'firstname' => $employee->_firstname,
                    'phoneNumber' => $employee->_phoneNumber
                ];
                header('Location: ../controllers/home-controller.php');
                exit();
            }
        }
    }
}

?>

<!-- nous incluons la vue login-view.php -->
<?php include_once '../views/login-view.php' ?>