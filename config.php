<?php
// Définition des constantes de connexion à la base de données : mdp, login, nom de la base, host ...
define('DB_HOST', 'localhost');
define('DB_NAME', 'expense');
define('DB_USER', 'expense-user');
define('DB_PASS', 'expense-password');

// Définition des regex sous forme de constante
define('REGEX_NAME', '/^[a-zA-ZÀ-ÖØ-öø-ÿ\' -]+$/');
define('REGEX_PHONENUMBER', '/^[01|02|03|04|05|06|07|09]\d{9}$/');

// Définition des paramètres d'upload de fichiers
define('UPLOAD_MAX_SIZE', 8 * 1000 * 1000);
define('UPLOAD_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Définition des constantes pour les statuts des notes de frais (1 = en attente, 2 = validée, 3 = refusée)
// cela va nous permettre de définir la couleur du badge dans la vue home-view.php
define('STATUS', [
    1 => 'primary',
    2 => 'success',
    3 => 'danger'
]);
