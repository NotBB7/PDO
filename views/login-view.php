<?php include_once 'template/head-login.php'; ?>

<div class="row mx-0 justify-content-evenly text-center">

    <div class="col-lg-4 col-10 mt-3 d-lg-block d-none">
        <img src="../assets/img/credit-report-svgrepo-com.png" alt="Icone du site" class="img-fluid border border-dangergit ">
    </div>

    <div class="col-lg-4 col-10 bg-module shadow rounded pt-3 pb-lg-0 pb-3">
        <p class="h3">Connexion</p>
        <form action="" method="POST" novalidate>
            <div class="login-error"><?= $errors['mail'] ?? '' ?></div>
            <input class="d-block mx-auto mb-2 text-center" type="mail" name="mail" placeholder="Identifiant" value="<?= $_POST['mail'] ?? '' ?>" required>
            <div class="login-error"><?= $errors['password'] ?? '' ?></div>
            <input class="d-block mx-auto mb-2 text-center" type="password" name="password" placeholder="Mot de passe" required>
            <div class="login-error"><?= $errors['signIn'] ?? '' ?></div>
            <button class="btn btn-primary my-2" type="submit">Connexion</button>
        </form>
        <a class="d-block text-decoration-none text-dark" href="../controllers/register-controller.php">Inscription</a>
    </div>

</div>

<?php include_once 'template/footer.php'; ?>