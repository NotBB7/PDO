<?php

// nous ouvrons une session
session_start();

// nous vérifions si l'utilisateur est connecté à l'aide de la variable de session user
// si l'utilisateur n'est pas connecté, nous le redirigeons vers la page de connexion
if (!isset($_SESSION['user'])) {
    header('Location: ../controllers/login-controller.php');
    exit();
}

// j'inclus les fichiers nécessaires se trouvant dans le fichier config.php
require_once '../config.php';

// j'inclus les fichiers nécessaires se trouvant dans le dossier helpers
require_once '../helpers/Database.php';
require_once '../helpers/Form.php';

// j'inclus les fichiers nécessaires se trouvant dans le dossier models type
require_once '../models/Type.php';
require_once '../models/Expense_report.php';

if (isset($_GET['expense'])) {

    // nous allons vérifier que expense est bien de type numérique
    if (!is_numeric($_GET['expense'])) {
        // si la dépense est vide, nous redirigeons l'utilisateur vers la page d'accueil
        header('Location: ../controllers/home-controller.php');
        exit();
    } else {
        // Nous récupérons les infos de la dépense
        $expense = Expense_report::getExpense($_GET['expense']);

        // Nous vérifions que les données de la dépense n'est pas vide = n'éxiste pas
        if (empty($expense)) {
            // si la dépense est vide, nous redirigeons l'utilisateur vers la page d'accueil
            header('Location: ../controllers/home-controller.php');
            exit();
            // si la dépense n'est pas en attente de validation cad = 1
        } else if ($expense['sta_id'] != 1) {
            // si la dépense est vide, nous redirigeons l'utilisateur vers la page d'accueil
            header('Location: ../controllers/home-controller.php');
            exit();
            // si la dépense n'est pas vide, nous vérifions que l'utilisateur est bien le propriétaire de la dépense
         } else if ($expense['emp_id'] != $_SESSION['user']['id']) {
                // si l'utilisateur n'est pas le propriétaire de la dépense, nous le redirigeons vers la page d'accueil
                header('Location: ../controllers/home-controller.php');
                exit();
            }
        }
    }

    // Nous définissons un tableau d'erreurs
    $errors = [];

    // Nous définissons une variable permettant de cacher / afficher le formulaire de note de frais, de base = true
    $showForm = true;

    // Déclenchement des actions uniquement à l'aide d'un POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Contrôle du date : vide
        if (isset($_POST['date'])) {
            if (empty($_POST['date'])) {
                $errors['date'] = 'La date est obligatoire';
            }
        }

        // Contrôle du type : vide
        if (!isset($_POST['type'])) {
            $errors['type'] = 'veuillez choisir un type de frais';
        }

        // Contrôle du type : être un entier
        if (isset($_POST['type'])) {
            if (!is_numeric($_POST['type'])) {
                $errors['type'] = 'ce type de frais n\'existe pas';
            }
        }

        // Contrôle du amount : vide et uniquement des nombres
        if (isset($_POST['amount'])) {
            if (empty($_POST['amount'])) {
                $errors['amount'] = 'Le montant TTC est obligatoire';
            } else if (!is_numeric($_POST['amount'])) {
                $errors['amount'] = 'Le montant TTC doit être un nombre';
            }
        }

        // Contrôle du motif : vide
        if (isset($_POST['description'])) {
            if (empty($_POST['description'])) {
                $errors['description'] = 'Le motif est obligatoire';
            }
        }

        // nous récupérons le justificatif de la dépense et nous 
        $proof = $expense['exp_proof'];

        // Contrôle du justificatif : si la personne souhaite la modifier
        if (isset($_FILES['proof'])) {
            // si le code d'erreur est égal à 4, cela signifie que l'utilisateur n'a pas téléchargé de fichier
            if ($_FILES['proof']['error'] == 0) {
                // nous récupérons le type du fichier avec son type mime et son extension : ex. image/png
                $mimeUserFile = mime_content_type($_FILES["proof"]["tmp_name"]);

                // nous utilisons la fonction explode() pour séparer le type mime et l'extension
                $type = explode('/', $mimeUserFile)[0];
                $extension = explode('/', $mimeUserFile)[1];

                // nous vérifions que le type du fichier est bien un fichier image
                if ($type != 'image') {
                    $errors['proof'] = 'Le justificatif doit être une image';

                    // nous vérifions que l'extension du fichier est bien une extension autorisée
                } elseif (!in_array($extension, UPLOAD_EXTENSIONS)) {
                    $errors['proof'] = 'Le justificatif doit être une image de type jpg, jpeg, png, gif ou webp';

                    // nous vérifions que la taille du fichier ne dépasse pas la taille maximale autorisée
                } elseif ($_FILES['proof']['size'] > UPLOAD_MAX_SIZE) {
                    $errors['proof'] = 'Le justificatif ne doit pas dépasser ' . UPLOAD_MAX_SIZE / 1000 . ' Ko';
                }

                // Nous allons convertir le fichier en base64 pour le stocker dans la base de données
                // nous récupérons le contenu du fichier
                $userFile = file_get_contents($_FILES['proof']['tmp_name']);

                // il nous reste plus qu'à encoder le contenu du fichier en base64 pour définir la variable $proof
                $proof = base64_encode($userFile);
            }
        }

        // si le tableau d'erreurs est vide, on ajoute la note de frais dans la base de données
        if (empty($errors)) {

            // nous récupérons la valeur de la variable $proof et nous la stockons dans la variable $userFileIn64
            // elle aura une valeur différente selon le fait que l'utilisateur ait téléchargé un fichier ou non
            $userFileIn64 = $proof;

            if (Expense_report::updateExpenseReport($_POST, $userFileIn64, $_SESSION['user']['id'], $expense['exp_id'], $expense['exp_id'])) {
                // nous mettons à jour la variable $showForm pour ne plus afficher le formulaire
                $showForm = false;
            } else {
                // nous mettons en place un message d'erreur dans le cas où la requête échouée
                $errors['bdd'] = 'Une erreur est survenue lors de la modification de votre note de frais';
            }
        }
    }

?>

<!-- nous incluons la vue register-view.php -->
<?php include_once '../views/update-expense-view.php'; ?>