<?php
// Nous ouvrons une session
session_start();

// si l'utilisateur dispose d'une session active, je le redirige vers le menu admin en ayant au préalable détruit sa session
if (isset($_SESSION['user'])) {
    session_unset();
    session_destroy();
    header('Location: ../controllers/login-controller.php');
    exit;
} else {
    // sinon je le redirige vers la page de connexion
    header('Location: ../controllers/login-controller.php');
    exit;
}
