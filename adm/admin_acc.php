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
        <h1>Suppression avec accord PO</h1>
        <center>
            <form action='admin_acc.php' method='post'>";
        $results = $dbh->query("SELECT SIREN FROM banque_clients");

// Vérifier s'il y a des résultats
if ($results->rowCount() > 0) {
    echo "SIREN <select id='siren' name='siren' required>";
    while ($ligne = $results->fetch(PDO::FETCH_OBJ)) {
       $option = $ligne->SIREN;
       echo "<option value='$option'> $option </option>";
    }
            echo "</select>";
}
           echo"                 <input type='submit' value='Valider' name='submit1'>

 
 </form>
        </center>
    </div>";


echo "
    <div class='box'>
        <h1>Création compte utilisateur</h1>
        <center>
            <form action='admin_acc.php' method='post'>
                <br>
                <label for='siren'> Siren </label>
                <input type='text' id='siren' name='siren' required>
                <br>
                <label for='raison'> Raison</label>
                <input type='text' id='raison' name='raison' required>
                <br>
                <label for='login'> Login</label>
                <input type='text' id='login' name='login' required>
                 <br>  <br>
                <input type='submit' value='Valider' name='submit2'>
            </form>
        </center>
    </div>";

if (isset($_POST['submit1'])){
    $siren = $_POST['siren'];

    $results = $dbh->query("INSERT INTO banque_validation (SIREN) VALUES ('$siren')");

}


if (isset($_POST['submit2'])) {
    $siren = $_POST['siren'];
    $raison = $_POST['raison'];
    $login = $_POST['login'];

    // Ensure proper validation and sanitization of user inputs to prevent SQL injection
    // Use prepared statements or parameterized queries for better security

    $results = $dbh->query("INSERT INTO banque_clients (SIREN, Raison_sociale, Login, solde) VALUES ('$siren', '$raison', '$login', 0)");
}