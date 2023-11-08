<?php include_once 'template/head.php'; ?>

<h1 class="text-center mt-4 mb-2 font-pangolin">Inscription</h1>

<div class="row justify-content-center mx-0 mb-5">
    <div class="container col-lg-8 col-10 px-lg-5 px-3 pb-5 rounded shadow bg-light">

        <div class="form-error my-3 text-center"><?= $errors['bdd'] ?? '' ?></div>

        <?php if ($showForm) { ?>

            <!-- novalidate permet de désactiver la validation HTML5 lorsqu'il y a des required-->
            <form action="" method="POST" novalidate>

                <div class="row justify-content-center mx-0">
                    
                    <div class="col-lg-6 px-3">

                        <div class="mb-4">
                            <label for="weight" class="form-label">Nom *</label>
                            <span class="form-error"><?= $errors['lastname'] ?? '' ?></span>
                            <input type="text" class="form-control" name="lastname" id="lastname" value="<?= $_POST['lastname'] ?? '' ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="weight" class="form-label">Prénom *</label>
                            <span class="form-error"><?= $errors['firstname'] ?? '' ?></span>
                            <input type="text" class="form-control" name="firstname" id="firstname" value="<?= $_POST['firstname'] ?? '' ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="weight" class="form-label">Courriel *</label>
                            <span class="form-error"><?= $errors['mail'] ?? '' ?></span>
                            <input type="text" class="form-control" name="mail" id="mail" value="<?= $_POST['mail'] ?? '' ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="weight" class="form-label">Numéro de contact *</label>
                            <span class="form-error"><?= $errors['phoneNumber'] ?? '' ?></span>
                            <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" value="<?= $_POST['phoneNumber'] ?? '' ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="weight" class="form-label">Mot de passe *</label>
                            <span class="form-error"><?= $errors['password'] ?? '' ?></span>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>

                        <div class="mb-4">
                            <label for="weight" class="form-label">Confirmation Mot de passe *</label>
                            <span class="form-error"><?= $errors['confirmPassword'] ?? '' ?></span>
                            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary font-pangolin mb-lg-0 mb-3">Enregistrer</button>
                            <a href="../controllers/home-controller.php" class="btn btn-outline-secondary font-pangolin">Annuler</a>
                            <p class="mt-3">* Champs obligatoires</p>
                        </div>

                    </div>

                </div>

            </form>

        <?php } else { ?>
            <!-- Nous indiquons que tout est ok -->
            <p class="text-center font-pangolin h3">Votre compte a bien été créé.<br>Vous pouvez maintenant vous connecter.</p>
            <div class="text-center py-3">
                <a href="../controllers/login-controller.php" class="btn btn-primary font-pangolin m-1">Connexion</a>
            </div>

        <?php } ?>
    </div>
</div>

<?php include_once 'template/footer.php'; ?>