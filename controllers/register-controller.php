<?php

// nous ouvrons une session
session_start();


// j'inclus les fichiers nécessaires se trouvant dans le fichier config.php
require_once '../config.php';

// j'inclus les fichiers nécessaires se trouvant dans le dossier helpers
require_once '../helpers/Database.php';
require_once '../helpers/Form.php';

// j'inclus les fichiers nécessaires se trouvant dans le dossier models Employees.php
require_once '../models/Employees.php';


// Nous définissons un tableau d'erreurs
$errors = [];

// Nous définissons une variable permettant cacher / afficher le formulaire d'inscription : de base = true
$showForm = true;

// Déclenchement des actions uniquement à l'aide d'un POST sur le bouton d'inscription
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Contrôle du nom : vide et pattern
    if (isset($_POST['lastname'])) {

        if (empty($_POST['lastname'])) {
            $errors['lastname'] = 'Le nom est obligatoire';
        } else if (!preg_match(REGEX_NAME, $_POST['lastname'])) {
            $errors['lastname'] = 'Le nom n\'est pas valide';
        }
    }

    // Contrôle du prénom : vide et pattern
    if (isset($_POST['firstname'])) {

        if (empty($_POST['firstname'])) {
            $errors['firstname'] = 'Le nom est obligatoire';
        } else if (!preg_match(REGEX_NAME, $_POST['firstname'])) {
            $errors['firstname'] = 'Le prénom n\'est pas valide';
        }
    }

    // Contrôle du mail : vide et pattern via filter_var
    if (isset($_POST['mail'])) {

        if (empty($_POST['mail'])) {
            $errors['mail'] = 'Le courriel est obligatoire';
            // utilisation de filter_var pour vérifier si le mail est valide
        } else if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
            $errors['mail'] = 'Le courriel n\'est pas valide';
        } else if (Employees::checkIfMailExist($_POST['mail'])) {
            $errors['mail'] = 'Le courriel est déjà utilisé';
        }
    }

    // Contrôle du mail : vide et pattern
    if (isset($_POST['phoneNumber'])) {

        if (empty($_POST['phoneNumber'])) {
            $errors['phoneNumber'] = 'Le numéro est obligatoire';
        } else if (!preg_match(REGEX_PHONENUMBER, $_POST['phoneNumber'])) {
            $errors['phoneNumber'] = 'Le numéro n\'est pas valide';
        }
    }

    // Contrôle du mdp : vide 
    if (isset($_POST['password'])) {

        if (empty($_POST['password'])) {
            $errors['password'] = 'Le mdp est obligatoire';
        }
    }

    // Contrôle du confirm mdp : vide 
    if (isset($_POST['confirmPassword'])) {

        if (empty($_POST['confirmPassword'])) {
            $errors['confirmPassword'] = 'Confirmation obligatoire';
        }
    }

    // Contrôle mdp et confirm mdp : identiques
    if (!empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
        if ($_POST['password'] !== $_POST['confirmPassword']) {
            $errors['confirmPassword'] = 'Les mots de passe ne sont pas identiques';
        }
    }

    // si le tableau d'erreurs est vide, on ajoute l'employé dans la base de données
    if (empty($errors)) {

        // nous utilisons la méthode static addEmployee de la classe Employees et nous lui passons en paramètre $_POST
        // si la méthode retourne true, on cache le formulaire à l'aide de la variable $showForm qui sera égale à false
        if (Employees::addEmployee($_POST)) {
            $showForm = false;
        } else {
            // nous mettons en place un message d'erreur dans le cas où la requête échouée
            $errors['bdd'] = 'Une erreur est survenue lors de la creation de votre compte';
        }
    }
}

?>

<!-- nous incluons la vue register-view.php -->
<?php include_once '../views/register-view.php'; ?>


