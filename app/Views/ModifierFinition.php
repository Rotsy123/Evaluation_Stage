<?= $this->extend("layouts/headeradmin") ?>

<?= $this->section("body") ?>
<?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>
    <?php if ($finition !== null): ?> <!-- Vérifie si $finition est défini -->
        <!-- Formulaire de mise à jour de produit -->
        <form action="<?= site_url('updatefinition') ?>" method="post">
            <input type="hidden" name="id" value="<?= $finition['id'] ?>">   
            <div class="container-fluid">
                <div class="form-group row">
                    <label for="nom">NOM</label>
                    <input type="text" name="nom" value="<?= $finition['nom']?>" class="form-control form-control-user" readonly>
                </div>
                <div class="form-group row">
                    <label for="pourcentage">POURCENTAGE</label>
                    <input type="number" step="0.01" name="pourcentage" value="<?= $finition['pourcentage'] ?>" class="form-control form-control-user">
                </div>
                <button type="submit">Mettre à jour la finition</button>
            </div>
        </form>
    <?php else: ?>
        <p>Les données de la finition ne sont pas disponibles.</p>
    <?php endif; ?>
<?= $this->endSection() ?>
