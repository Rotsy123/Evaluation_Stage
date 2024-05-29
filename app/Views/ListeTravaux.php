<?= $this->extend("layouts/headeradmin") ?>

<?= $this->section("body") ?>
    <style type="text/css">
        a {
            padding-left: 5px;
            padding-right: 5px;
            margin-left: 5px;
            margin-right: 5px;
        }

        .pagination li.active {
            background: deepskyblue;
            color: white;
        }

        .pagination li.active a {
            color: white;
            text-decoration: none;
        }
    </style>

<script>
      
        
    </script>
 
            <div class="card-body">
                <div class="table-responsive">
                    <div id="export-content">
        
                    <!-- <div style="width: 300px; margin: auto;"> Ajustez la largeur du div parent selon vos besoins -->
                        <!-- <canvas id="myChart" width="150" height="150"></canvas> -->
                    <!-- </div> -->
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th>
                                ID Detailtravaux
                            </th>
                            
                            <th>
                                ID Travaux
                            </th>
                            <th>
                                Nom Travaux
                            </th>
                            <th>
                                Quantite
                            </th>
                            <th>
                                Prix
                            </th>
                            <th>
                                Unite
                            </th>
                            <th>
                                Maison
                            </th>
                        </thead>
                        <tbody>
                            <?php foreach ($travaux as $travail): ?> 
                                <tr>
                                    <td><?= $travail->iddetailtravaux?></td>
                                    <td><?= $travail->idtravaux ?></td>
                                    <td><?= $travail->nomtravaux ?></td>
                                    <td><?= $travail->quantite?></td>
                                    <td><?= $travail->prix?></td>
                                    <td><?= $travail->nomunite ?></td>
                                    <td><?= $travail->nommaison ?></td>
                                    <td>
                                        <form action="<?php echo base_url("mettreajour")?>" method="post">
                                            <input type="hidden" name="travaux" id="travaux" value="<?= htmlentities(json_encode($travail)) ?>">
                                            <button class="export-btn">MODIFIER</button> 
                                        </form>                 
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
<?= $this->endSection() ?>
 

