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
        function exportToPDF(maisonId) {
    var element = document.getElementById('export-content'); // Sélectionnez la zone spécifique à exporter
    
    // Envoie des données au serveur via AJAX
    $.ajax({
        url: '<?php echo base_url("AfficherDetail"); ?>',
        type: 'POST',
        data: { 
            content: element.innerHTML, // Envoyez le contenu HTML à exporter
            maison: maisonId // Ajoutez la valeur de la maison
        },
        success: function(response) {
            // Gérer la réponse ici, si nécessaire
            // Par exemple, vous pouvez rediriger l'utilisateur vers le PDF généré
            window.location.href = response.pdf_url;
            console.log(window.location.href);
        },
        error: function(xhr, status, error) {
            // Gérer les erreurs ici, si nécessaire
            console.error(error);
        }
    });
}

        
        
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
                                Date du devis
                            </th>
                            <th>
                                DEBUT
                            </th>
                            <th>
                                FIN
                            </th>
                            <th>
                                DUREE
                            </th>
                            <th>
                                Type de Maison
                            </th>
                            <th>
                                Type de Finition
                            </th>
                            <th>
                                Identifiant
                            </th>
                            <th>
                                TOTAL DES PRIX
                            </th>
                            <th>
                                PAIEMENT EFFECTUR
                            </th>
                            <th>
                                POURCENTAGE 
                            </th>
                        </thead>
                        <tbody>
                            <?php foreach ($listedevis as $devis): ?> 
                                <tr>
                                

                                    <td><?= $devis->datedevis?></td>
                                    <td><?= $devis->debut?></td>
                                    <td><?= $devis->fin?></td>

                                    <td><?= $devis->jours?></td>
                                    <td><?= $devis->nom_maison?></td>
                                    <td><?= $devis->nom_finition ?></td>
                                    <td><?= $devis->phone ?></td>
                                    <td><?= $devis->prix_total_finition ?></td>
                                    <td><?= $devis->total_paiements ?></td>
                                    <?php if($devis->pourcentage<50){?>
                                    <td style="background-color: red; color:white;"><?= $devis->pourcentage ?></td>
                                    <?php }else if($devis->pourcentage>50){?>
                                        <td style="background-color: green; color:white;"><?= $devis->pourcentage ?></td> 
                                    <?php } else{?>
                                        <td style="background-color: white; color:black;"><?= $devis->pourcentage ?></td> 

                                    <?php } ?>
                                    <td>
                                        <form action="<?php echo base_url("AfficherDetail")?>" method="post">
                                            <input type="hidden" name="devis" id="devis" value="<?php echo $devis->id?>">
                                            <input type="hidden" name="finition" id="finition" value="<?php echo $devis->idfinition?>">
                                            <button class="export-btn" data-maison="<?php echo $devis->idmaison ?>">AFFICHER LES DETAILS</button> 
                                        </form>                 
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
<?= $this->endSection() ?>
 

