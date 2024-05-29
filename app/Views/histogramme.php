<?= $this->extend("layouts/header") ?>

<?= $this->section("body") ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('myAreaCharts').getContext('2d');

        var data = {
            labels: <?= json_encode($noms) ?>,
            datasets: [{
                label: 'Devise annuelle',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                data: <?= json_encode($prix) ?>,
            }]
        };

        var options = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        var myAreaCharts = new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    });
</script>

<canvas id="myAreaCharts"></canvas>
<?= $this->endSection() ?>
