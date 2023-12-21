<link rel="stylesheet" href="styles.css">
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
<script defer src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script defer src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script defer src="script.js"></script>
<script  src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js'></script>
<script  src='https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js'></script>
<script defer src="TableCSVExporter.js"> </script>
<script src="bower_components\jquery\dist\jquery.min.js"></script>
<script src="bower_components\jquery-table2excel\dist\jquery.table2excel.min.js"></script>
<script src="table2excel.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>


<?php
include 'menu.php';
require_once('confbdd.php');
echo "
    <div class='box'>
        <h1>Graphique des Impayés</h1>
        <center>
            <form action='graphique_po.php' method='post'>
                <br>
                <label for='dated'> Date de début</label>
                <input type='date' id='dated' name='dated' required>
                <br>
                <label for='datef'> Date de fin</label>
                <input type='date' id='datef' name='datef' required>
                <input type='submit' value='Valider' name='submit'>
            </form>
        </center>
    </div>";


if (isset($_POST['submit'])) {
    $dated = $_POST['dated'];
    $datef = $_POST['datef'];
    if ($dated > $datef) {
        echo "Rentrez une date de fin inférieure à la date de début";
    } else {
        // Utilisation de la variable $dbh pour exécuter la requête SQL
        $results = $dbh->query("
            SELECT DATE_VENTE, SUM(MONTANT) AS MontantTotal
            FROM banque_transaction 
            WHERE STATUT = 0 AND DATE_VENTE > '$dated' AND DATE_VENTE < '$datef'
            GROUP BY DATE_VENTE
        ");
        echo "

            <canvas id='myChart' width='400' height='200'></canvas>
            <canvas id='myPieChart' width='400' height='200'></canvas>
            <button id='exportPNG'>Export PNG</button>
            <button id='exportPDF'>Export PDF</button>

        ";
    }
}
?>

<script>
    $(document).ready(function () {
        // Récupérer les données depuis PHP
        var dates = [];
        var montants = [];

        <?php
        while ($ligne = $results->fetch(PDO::FETCH_OBJ)) {
            echo "dates.push('{$ligne->DATE_VENTE}');";
            echo "montants.push('{$ligne->MontantTotal}');";
        }
        ?>

        // Créer le graphique en barres avec Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Montant en Euro',
                    data: montants,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Créer le graphique en camembert avec Chart.js
        var pieCtx = document.getElementById('myPieChart').getContext('2d');
        var myPieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: dates,
                datasets: [{
                    data: montants,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });

        $('#exportPNG').click(function () {
            html2canvas(document.getElementById('myChart')).then(function (canvas) {
                var imgData = canvas.toDataURL('image/png');
                var img = new Image();
                img.src = imgData;
                var pdf = new jsPDF('p', 'mm', 'a4');
                pdf.addImage(img, 'PNG', 10, 10, 190, 100);
                pdf.save('chart.png');
            });
        });

        // Exporter en PDF
        $('#exportPDF').click(function () {
            html2canvas(document.getElementById('myChart')).then(function (canvas) {
                var imgData = canvas.toDataURL('image/png');
                var pdf = new jsPDF('p', 'mm', 'a4');
                pdf.addImage(imgData, 'PNG', 10, 10, 190, 100);
                pdf.save('chart.pdf');
            });
        });
    });
</script>
