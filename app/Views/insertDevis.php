<?= $this->extend("layouts/header") ?>

<?= $this->section("body") ?>

<form action="<?= base_url('insertDevis') ?>" method="post" class="user">
    <div class="container-fluid">
        <div class="form-group row">
            <label for="debut">Date de début des constructions</label>
            <input type="date" name="debut" placeholder="Début des travaux" class="form-control form-control-user">
        </div>

        <br><br>

        <div class="form-group row">
            <?php for ($i = 0; $i < count($maisons); $i++) { ?>
                <?php if ($i % 3 == 0) { ?> <!-- Démarre une nouvelle ligne toutes les 3 maisons -->
                    <div class="row mb-3">
                <?php } ?>
                <div class="col-md-4 custom-radio-container"> <!-- Utilise Bootstrap grid pour aligner les boutons en 3 colonnes -->
                    <!-- <label for="maisons">MAISON</label> -->
                    <label class="custom-radio">
                        <input type="radio" name="maisons" value="<?= $maisons[$i]['id'] ?>" checked class="form-control form-control-user">
                        <?= $maisons[$i]['descriptions'] ?>,<?= $maisons[$i]['nom'] ?>
                    </label>
                </div>
                <?php if (($i + 1) % 3 == 0 || $i + 1 == count($maisons)) { ?> <!-- Ferme la ligne toutes les 3 maisons ou à la fin -->
                    </div>
                <?php } ?>
            <?php } ?>
        </div>

        <br><br>

        <div class="form-group row">
            <label for="finitions">FINITION</label>
            <?php for ($i = 0; $i < count($finitions); $i++) { ?>
                <?php if ($i % 3 == 0) { ?> <!-- Démarre une nouvelle ligne toutes les 3 finitions -->
                    <div class="row mb-3">
                <?php } ?>
                <div class="col-md-4 custom-radio-container"> <!-- Utilise Bootstrap grid pour aligner les boutons en 3 colonnes -->
                    <label>
                        <input type="radio" name="finitions" value="<?= $finitions[$i]['id'] ?>" checked class="form-control form-control-user">
                        <?= $finitions[$i]['nom'] ?>
                    </label>
                </div>
                <?php if (($i + 1) % 3 == 0 || $i + 1 == count($finitions)) { ?> <!-- Ferme la ligne toutes les 3 finitions ou à la fin -->
                    </div>
                <?php } ?>
            <?php } ?>
        </div>

        <br><br>

        <div class="form-group row">
            <label for="finitions">LIEU</label>
            <input type="text-area" name="lieu" class="form-control form-control-user">
        </div>

        <button type="submit" class="btn btn-facebook btn-block">Ajouter produit</button>
    </div>
</form>

<?= $this->endSection() ?>
