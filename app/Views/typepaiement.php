<?= $this->extend("layouts/header") ?>

<?= $this->section("body") ?>
<div id="error-container" class="alert alert-danger" style="display: none;"></div>

<?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>
    <?php if ($type !== null){ ?> <!-- Vérifie si $finition est défini -->

        <div class="container-fluid">
            <form id="myForm" action="<?php echo base_url("validerchoix")?>" method="POST">
                <div class="form-group row">
                <input type="hidden" name="devis" type="devis" value="<?php echo $devis ?>">
                    <!-- <label class="custom-radio"> -->
                        
                    <!-- </label>  -->
                </div>
                <div id="paiement">
                        <div class="form-group row">
                        <!-- Premier div avec les champs initiaux -->
                            <label for="texts">ENTRER LE PRIX A PAYE</label>
                            <input type="number" name="texts" step="0.01" class="form-control form-control-user">
                        </div> 
                        <div class="form-group row">
                            <label for="date">ENTRER LA DATE DU PAIEMENT</label>
                            <input type="date" name="dates" class="form-control form-control-user">
                        </div>
                </div><br>
                    <button type="submit" id="submitBtn" class="export-btn">Submit</button>

            </form>
        </div>
<?php } ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>


$(document).ready(function() {
    $('#myForm').submit(function(e) {
        e.preventDefault(); // Empêcher l'envoi du formulaire par défaut

        $.ajax({
            url: '<?php echo base_url("validerchoix")?>',
            type: 'POST',
            dataType: 'json',
            data: $('#myForm').serialize(), // Sérialiser le formulaire pour envoyer les données
            success: function(response) {
                if (response.success) {
                    // Les données sont valides, effectuer l'action souhaitée
                    window.location.href = '<?php echo base_url("listeDevis")?>';
                } else {
                    console.log("helloooo");

                    // Afficher les erreurs sur la page
                    $('#error-container').text(response.errors).show(); // Afficher le message d'erreur
                }
            },
            error: function(xhr, status, error) {
                // Gérer les erreurs de requête
                
                console.log("helloooo");
                console.error(xhr.responseText);
                var response = JSON.parse(xhr.responseText);

                // Accéder à la clé "errors" et l'afficher dans une boîte de dialogue
                alert(response.errors);
            }
        });
    });
});

        document.getElementById('addBtn').addEventListener('click', function() {
    // Créer un nouveau div avec les champs
    var newDiv = document.createElement('div');
    newDiv.classList.add('form-group', 'row');

    // Créer et configurer le champ de saisie pour le prix à payer
    var labelPrix = document.createElement('label');
    labelPrix.setAttribute('for', 'text[]');
    labelPrix.textContent = 'ENTRER LE PRIX À PAYER';

    var inputPrix = document.createElement('input');
    inputPrix.setAttribute('type', 'number');
    inputPrix.setAttribute('step', '0.01');
    inputPrix.setAttribute('name', 'text[]');
    inputPrix.classList.add('form-control', 'form-control-user');

    // Créer et configurer le champ de saisie pour la date du paiement
    var labelDate = document.createElement('label');
    labelDate.setAttribute('for', 'date[]');
    labelDate.textContent = 'ENTRER LA DATE DU PAIEMENT';

    var inputDate = document.createElement('input');
    inputDate.setAttribute('type', 'date');
    inputDate.setAttribute('name', 'date[]');
    inputDate.classList.add('form-control', 'form-control-user');

    // Ajouter les champs au div
    newDiv.appendChild(labelPrix);
    newDiv.appendChild(inputPrix);
    newDiv.appendChild(labelDate);
    newDiv.appendChild(inputDate);

    // Ajouter le div au conteneur "paiement"
    document.getElementById('paiement').appendChild(newDiv);
    
});

    </script>
    <?= $this->endSection() ?>
