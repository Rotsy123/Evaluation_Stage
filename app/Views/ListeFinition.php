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
                                NOM
                            </th>
                            
                            <th>
                                Pourcentage
                            </th>
                        </thead>
                        <tbody>
                            <?php foreach ($finition as $fin): ?> 
                                <tr>
                                    <td><?= $fin['nom']?></td>
                                    <td><?= $fin['pourcentage'] ?></td>
                                    <td>
                                        <form action="<?php echo base_url("mettreajourfinition")?>" method="post">
                                            <input type="hidden" name="fin" id="fin" value="<?= htmlentities(json_encode($fin)) ?>">
                                            <button class="export-btn">AFFICHER LES DETAILS</button> 
                                        </form>                 
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
<?= $this->endSection() ?>
 

