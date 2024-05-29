<?= $this->extend("layouts/header") ?>

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
        td {
            text-align: center; /* Alignement horizontal au centre */
            vertical-align: middle; /* Alignement vertical au centre */
        }

.export-btn {
    font-size: 16px;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

.export-pdf {
    background-color: #007bff; /* Bleu */
    color: #fff; /* Blanc */
}

.choisir-paiement {
    background-color: #28a745; /* Vert */
    color: #fff; /* Blanc */
}
    </style>

<script>
        function exportToPDF(maisonId) {
    var element = document.getElementById('export-content'); // Sélectionnez la zone spécifique à exporter
    
    // Envoie des données au serveur via AJAX
    $.ajax({
        url: '<?php echo base_url("getSousDevis"); ?>',
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
                                Type de Maison
                            </th>
                            <th>
                                Type de Finition
                            </th>
                            <th>
                                Debut
                            </th>
                            <th>
                                FIN
                            </th>
                            <th>
                                EXPORTER EN PDF
                            </th>
                        </thead>
                        <tbody>
                            <?php foreach ($listedevis as $devis): ?> 
                                <tr>
                                    <td><?= $devis['datedevis'] ?></td>
                                    <td><?= $devis['maison']['nom'] ?></td>

                                    <td><?= $devis['finition']['nom'] ?></td>
                                    <td><?= $devis['date']['debut'] ?></td>
                                    <td><?= $devis['date']['fin'] ?></td>

                                    <td>
                                        <form action="<?php echo base_url("getSousDevis")?>" method="post">

                                            <input type="hidden" name="devis" id="devis" value="<?php echo $devis['iddevis']?>">
                                            <input type="hidden" name="finition" id="finition" value="<?php echo $devis['finition']['id']?>">
                                            <button class="export-btn export-pdf" data-maison="<?php echo $devis['maison']['id'] ?>">Exporter en PDF</button> 
                                        </form>                
                                        <br>
                                        <form action="<?php echo base_url("choisirPaiement")?>" method="post">
                                            <input type="hidden" name="devis" id="devis" value="<?php echo $devis['iddevis']?>">
                                            <button class="export-btn choisir-paiement" data-maison="<?php echo $devis['iddevis']?>">CHOISIR LE PAIEMENT</button> 
                                        </form>                
                                    </td>

                                </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
<?= $this->endSection() ?>
 

