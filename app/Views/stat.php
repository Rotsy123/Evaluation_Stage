<?= $this->extend("layouts/headeradmin") ?>

<?= $this->section("body") ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

    <!-- Content Row -->
<div class="row">

                        <!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Prix des devis en cours</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($prixencours,2,',','.')?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Prix des devis Effectues</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($paiement,2,',','.')?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
                 
                        <!-- Pending Requests Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Montant Total des devis</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($prixall,2,',','.')?></div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


                    <!-- Content Row -->

<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <?php foreach($annee as $an) { ?>
                            <a class="dropdown-item" href="#" onclick="sendData('<?php echo $an->annee ?>')"><?php echo $an->annee ?></a>
                        <?php }?>
                        <a class="dropdown-item" href="#" onclick="sendData('0')"><?php echo 0?></a>



                    </div>
                </div>
            </div>
<script>
    function sendData(year) {
    // Créer un formulaire temporaire
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('statistique') ?>'; // URL de votre contrôleur

    // Créer un champ de formulaire pour l'année
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'annee'; // Nom du champ de formulaire
    input.value = year; // Valeur de l'année
    form.appendChild(input);

    // Ajouter le formulaire à la page et le soumettre
    document.body.appendChild(form);
    form.submit();
}

</script>

            <!-- Card Body -->
            <div class="card-body">
                <?php if (isset($mois)) { ?>
                   <!-- Assurez-vous d'inclure la bibliothèque Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Ajoutez le code JavaScript pour créer le graphique -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('myBarChart').getContext('2d');

        var data = {
            labels: <?= json_encode($mois) ?>,
            datasets: [{
                label: 'Devise mensuelle',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                data: <?= json_encode($total) ?>,
            }]
        };

        var options = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            barPercentage: 0.5
        };

        var myBarChart = new Chart(ctx, {
            type: 'bar', // Changement du type de graphique en 'bar'
            data: data,
            options: options
        });
    });
</script>

<!-- Ajoutez le canevas pour afficher le graphique -->
<canvas id="myBarChart"></canvas>

                <?php } ?> 
                
            </div>
        </div>
    </div>


<?= $this->endSection() ?>