<?= $this->extend("layouts/headeradmin") ?>

<?= $this->section("body") ?>
<style type="text/css">
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #f5f5f5;
    }
</style>

<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Désignation</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Unite</th>
                    <th>Maison</th>
                    <th>Total</th> <!-- Je ne suis pas sûr de la signification de cette colonne, je l'ai laissée en double -->
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($hierarchie)) : ?>
                    <?php foreach ($hierarchie as $row) : ?>
                        <tr> 
                            <td><?= $row->iddetailtravaux ?></td>
                            <td><?= $row->nomtravaux ?></td> 
                            <td><?= !empty($row->quantite) ? $row->quantite : '--' ?></td>
                            <td><?= !empty($row->prix) ? $row->prix : '--' ?></td> 
                            <td><?= !empty($row->nomunite) ? $row->nomunite : '--' ?></td> 

                            <td><?= !empty($row->nommaison) ? $row->nommaison : '--' ?></td>
                            <td><?= !empty($row->total) ? ($row->total) : '--' ?></td>
                        
                            
                            
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">Aucune donnée trouvée.</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

    <p>PRIX DES FINITIONS : <?= $finition ?></p>
    <p>PRIX TOTAL : <?= $prix ?></p>
</div>
<?= $this->endSection() ?>
