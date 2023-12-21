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




<?php
include 'menu.php';
require_once('confbdd.php') ;
$siren = $_COOKIE['siren_cookie'];

echo "
    <div class='box'>
        <h1>Annonce des Remises</h1>
        <center>
            <form action='remise_client.php' method='post'>
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
            SELECT t.SIREN, c.Raison_sociale, t.NumRemise, t.DateTraitement, COUNT(*) AS 'NbreTransactions', SUM(t.Montant) AS 'MontantTotal'
            FROM banque_transaction t
            JOIN banque_clients c ON t.SIREN = c.SIREN
            WHERE t.DateTraitement > '$dated' AND t.DateTraitement < '$datef' AND t.siren = '$siren'
            GROUP BY t.SIREN, c.Raison_sociale, t.NumRemise, t.DateTraitement
            ORDER BY t.SIREN, t.DateTraitement;
        ");

        echo "
        <table id='example' class='table table-striped ex' style='width:100%'>
            <thead>
                <th>N° Siren</th>
                <th>Raison Sociale</th>
                <th>N° De Remise</th>
                <th>Date de traitement</th>
                <th>Nbres transactions</th>
                <th>Devise</th>
                <th>Montant total</th>
                <th>Sens</th>
            </thead>
            <tbody>";

        while ($ligne = $results->fetch(PDO::FETCH_OBJ)) {
            // Calcul du sens
            $sens = ($ligne->MontantTotal < 0) ? '-' : '+';

            echo "
            <tr>
                <td>$ligne->SIREN</td>
                <td>$ligne->Raison_sociale</td>
                <td>$ligne->NumRemise</td>
                <td>$ligne->DateTraitement</td>
                <td>$ligne->NbreTransactions</td>
                <td>€</td>
                <td>$ligne->MontantTotal</td>
                <td>$sens</td>
            </tr>";
        }

        echo "</tbody></table>";
    }
}
?>