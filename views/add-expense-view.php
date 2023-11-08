<?php include_once 'template/head.php';
?>

<h1 class="text-center mt-4 mb-2 font-pangolin">Enregistrement</h1>

<div class="row justify-content-center mx-0 mb-5">
    <div class="container col-lg-8 col-10 px-lg-5 px-3 pb-5 rounded shadow bg-light">

        <div class="form-error my-3 text-center"><?= $errors['bdd'] ?? '' ?></div>

        <?php if ($showForm) { ?>

            <!-- novalidate permet de désactiver la validation HTML5 lorsqu'il y a des required-->
            <!-- penser à mettre enctype="multipart/form-data" pour les fichiers -->
            <form action="" method="POST" enctype="multipart/form-data" novalidate>
                
                <div class="row justify-content-center mx-0">

                    <div class="col-lg-6 px-3">

                        <div class="mb-4">
                            <label for="weight" class="form-label">Date *</label>
                            <span class="form-error"><?= $errors['date'] ?? '' ?></span>
                            <!-- nous mettons la date du jour à l'aide de la fonction date() de PHP -->
                            <input type="date" class="form-control" name="date" id="date" value="<?= $_POST['date'] ?? date('Y-m-d') ?>" required>
                        </div>

                        <label for="specie" class="form-label">Type de frais *</label>
                        <span class="form-error"><?= $errors['type'] ?? '' ?></span>
                        <select class="form-select form-select-sm mb-4" name="type" id="type">
                            <option value="" selected disabled>Choix du type</option>
                            <?php foreach (Type::getAllTypes() as $type) { ?>
                                <option value="<?= $type['typ_id'] ?>" data-tva="<?= $type['typ_tva'] ?>" <?= isset($_POST['type']) && $_POST['type'] == $type['typ_id'] ? 'selected' : '' ?>><?= $type['typ_name'] ?></option>
                            <?php } ?>
                        </select>

                        <div class="mb-4">
                            <label for="amount" class="form-label">Montant TTC * <i>(en €uros)</i></label>
                            <span class="form-error"><?= $errors['amount'] ?? '' ?></span>
                            <input type="number" class="form-control" id="amount" name="amount" value="<?= $_POST['amount'] ?? '' ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="amountHT" class="form-label">Montant HT * <i>(en €uros)</i></label>
                            <input type="text" class="form-control" id="amountHT" name="amountHT" value="<?= $_POST['amountHT'] ?? '' ?>" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="tva" class="form-label">TVA * <i>(en €uros)</i></label>
                            <input type="text" class="form-control" id="tva" name="tva" value="<?= $_POST['tva'] ?? '' ?>" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Motif *</label>
                            <span class="form-error"><?= $errors['description'] ?? '' ?></span>
                            <textarea class="form-control" id="description" name="description" placeholder="ex. Déplacement chez le client ... " rows="3"><?= $_POST['description'] ?? '' ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="proof" class="form-label">Justificatif *</label>
                            <span class="form-error"><?= $errors['proof'] ?? '' ?></span>
                            <input type="file" class="form-control" name="proof" id="proof" required>
                            <span class="text-dark"><?= isset($_FILES['proof']) && $_FILES['proof']['error'] != 4 ? 'Fichier sélectionné : ' . $_FILES['proof']['name'] : '' ?></span>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mb-lg-0 mb-3">Enregistrer</button>
                            <a href="../controllers/home-controller.php" class="btn btn-outline-secondary">Annuler</a>
                            <p class="mt-3">* Champs obligatoires</p>
                        </div>

                    </div>

                </div>

            </form>

        <?php } else { ?>
            <!-- Nous indiquons que tout est ok -->
            <p class="text-center h3">La note a bien été prise en compte.</p>
            <div class="text-center py-3">
                <a href="../controllers/add-expense-controller.php" class="btn btn-dark">+ Nouvelle note</a>
                <a href="../controllers/home-controller.php" class="btn btn-secondary">Accueil</a>
            </div>

        <?php } ?>
    </div>
</div>

<!-- appelle du JS pour le calcul en live -->
<script src="../assets/js/script.js"></script>

<?php include_once 'template/footer.php'; ?>