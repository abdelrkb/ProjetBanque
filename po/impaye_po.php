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
require_once('confbdd.php');

echo "
    <div class='box'>
        <h1>Annonce des Impayés</h1>
        <center>
            <form action='impaye_po.php' method='post'>
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
        SELECT SIREN, DATE_VENTE, DateTraitement, NumCarte, Reseau, NumDossierImp, MONTANT, LibelleImp FROM banque_transaction 
        WHERE STATUT=0 and DATE_VENTE > '$dated' and DATE_VENTE < '$datef' ");
        echo "
        <table id='example' class='table table-striped ex' style='width:100%'>
            <thead>
                <th>N° Siren</th>
                <th>Date vente</th>
                <th>Date remise </th>
                <th>N° Carte</th>
                <th>Réseau</th>
                <th>N° Dossier Impayé</th>
                <th>Devise</th>
                <th> Montant </th>
                <th> Libellé Impayé</th>
            </thead>
            <tbody>";

        while ($ligne = $results->fetch(PDO::FETCH_OBJ)) {
            echo "
            <tr>
                <td>$ligne->SIREN</td>
                <td>$ligne->DATE_VENTE</td>
                <td>$ligne->DateTraitement</td>
                <td>$ligne->NumCarte</td>
                <td>$ligne->Reseau</td>
                <td>$ligne->NumDossierImp</td>
                <td> € </td>
                <td>$ligne->MONTANT</td>
                <td>$ligne->LibelleImp</td>
            </tr>";
        }

        echo "</tbody></table>";

    }
}
?>

