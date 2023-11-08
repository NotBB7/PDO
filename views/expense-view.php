<?php include_once 'template/head.php' ?>

<div class="container mt-3">

    <h1 class="text-center my-2 font-pangolin">Détails</h1>

    <div class="row justify-content-center mx-0">
        <div class="col-6 p-4 mb-4 bg-light">

            <?php
            // Je mets en place une condition pour afficher une div lorsque la note de frais est validée ou refusée.
            if ($expense['sta_id'] == 2 || $expense['sta_id'] == 3) { ?>
                <div class="alert alert-<?= STATUS[$expense['sta_id']] ?>" role="alert">
                    <ul class="list-unstyled">
                        <li class="fw-bold"><?= Form::formatDateUsToFr($expense['exp_decision_date']) ?></li>
                        <li><?= $expense['exp_cancel_reason'] ?></li>
                    </ul>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col">
                    <p class="h1">Note de frais</p>
                    <p class="h3"><?= Form::formatDateUsToFr($expense['exp_date']) ?></p>
                </div>
                <div class="col">
                    <p class="text-end"><span class="badge bg-<?= STATUS[$expense['sta_id']] ?> rounded-pill"><?= $expense['sta_name'] ?></span></p>
                    <p class="h5 text-end fs-italic"><?= $expense['typ_name'] ?></p>
                </div>
            </div>

            <hr>
            <p class="h5 text-secondary"><?= $expense['exp_description'] ?></p>
            <hr>

            <ul class="list-unstyled">
                <li>Montant TTC : <span class="fw-bold"><?= $expense['exp_amount_ttc'] ?></span> €</li>
                <li>Montant HT : <span class="fw-bold"><?= $expense['exp_amount_ht'] ?></span> €</li>
                <li>Montant TVA (<?= $expense['typ_tva'] ?>%) : <span class="fw-bold"><?= $expense['exp_amount_ttc']  - $expense['exp_amount_ht'] ?></span> €</li>
            </ul>

            <hr>
            <p class="fw-bold">Justificatif</p>
            <img class="img-fluid border border-dark" src="data:image/png;base64,<?= $expense['exp_proof'] ?>" alt="Justificatif note de frais" target="_blank">
            <a class="btn btn-secondary my-4" href="../controllers/home-controller.php">Retour</a>

            <?php
            // nous mettons en place une condition pour afficher les boutons modifier et supprimer uniquement lorsque la note de frais est en attente de validation.
            if ($expense['sta_id'] == 1) { ?>
                <a class="btn btn-info ms-5" href="../controllers/update-expense-controller.php?expense=<?= $expense['exp_id'] ?>">Modifier</a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Supprimer</button>

            <?php } ?>

            <!-- Modal de confirmation -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <p class="modal-title fs-5">Suppression de la note</p>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <b>Souhaitez vous supprimer cette note ?</b>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <form action="" method="POST">
                                <button type="submit" name="delete" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

        </div>
    </div>
</div>

<?php include_once 'template/footer.php' ?>