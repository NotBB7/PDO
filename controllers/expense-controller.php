<?php
// nous ouvrons une session
session_start();

// nous vérifions si l'utilisateur est connecté à l'aide de la variable de session user
// si l'utilisateur n'est pas connecté, nous le redirigeons vers la page de connexion
if (!isset($_SESSION['user'])) {
    header('Location: ../controllers/login-controller.php');
    exit();
}

require_once '../config.php';
require_once '../helpers/Database.php';

require_once '../models/Employees.php';
require_once '../models/Expense_report.php';

require_once '../helpers/Form.php';

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
            // nous vérifions que l'id de l'utilisateur connecté est le même que l'id de l'utilisateur de la dépense
        } else if ($expense['emp_id'] != $_SESSION['user']['id']) {
            // si la dépense n'appartient pas à l'utilisateur connecté, nous redirigeons l'utilisateur vers la page d'accueil
            header('Location: ../controllers/home-controller.php');
            exit();
        }
    }
} else {
    // si l'id de la dépense n'est pas défini, nous redirigeons l'utilisateur vers la page d'accueil
    header('Location: ../controllers/home-controller.php');
    exit();
}

// nous verifions que qu'un post a été envoyé
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // nous vérifions que le bouton de confirmation de suppression a été cliqué
    if (isset($_POST['delete'])) {
        // nous supprimons la dépense
        Expense_report::deleteExpense($_GET['expense']);
        // nous redirigeons l'utilisateur vers la page d'accueil
        header('Location: ../controllers/home-controller.php');
        exit();
    }
}

?>


<!-- nous incluons la vue home-view.php -->
<?php include_once '../views/expense-view.php' ?>