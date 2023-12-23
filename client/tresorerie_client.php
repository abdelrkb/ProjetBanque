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
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start the session at the beginning

include 'menu.php';
require_once('confbdd.php');

// Vérification de la session
if (isset($_SESSION['siren'])) {
    $siren = $_SESSION['siren'];
} elseif (isset($_COOKIE['siren_cookie'])) {
    $siren = $_COOKIE['siren_cookie'];
    $_SESSION['siren'] = $siren; // Stocker dans la session pour persistencer
} elseif (isset($_POST['login'])) {
    $siren = $_POST['login'];
    $_SESSION['siren'] = $siren;
    setcookie('siren_cookie', $siren, time() + (86400 * 30), "/", "", true, true); // Sécurisation du cookie
}
session_regenerate_id(true);
echo "
<div class='box'>";

$results1 = $dbh -> query("SELECT SOLDE, Raison_sociale from banque_clients WHERE SIREN='$siren'");
while ($ligne = $results1->fetch(PDO::FETCH_OBJ)){
    echo "<h1> Solde Global</h1>";
    echo "<center> $ligne->Raison_sociale</center>";
    echo "<center> $ligne->SOLDE €</center>";
}


echo"
</div>
<div class='box'>
    <h1>Annonce de trésorerie par date</h1>
    <center>
        <form action='tresorerie_client.php' method='post'>
            <br>
            <label for='date'> Date de transaction</label>
            <input type='date' id='date' name='date' required>
            <br>
            <input type='submit' value='Valider' name='search1'>
        </form>
    </center>
</div>";

if (isset($_POST['search1'])) {
    $siren = $_COOKIE['siren_cookie'];
    $date = $_POST['date'];

    echo "<h1> Tableau des annonces</h1>";
    $date = $_POST['date'];
    $results1 = $dbh -> query("SELECT COUNT(*) AS nb, SUM(Montant) AS montantTotal, c.SIREN, c.Raison_sociale
    FROM banque_clients c, banque_transaction
    WHERE DATE_VENTE = '$date' AND c.SIREN = '$siren'");
    echo"
    <table class='table table-striped' style='width:100%'>
    <thead> 
        <th> N° Siren</th>
        <th> Raison Sociale</th>
        <th> Nombres Transactions</th>
        <th> Montant total</th>
    </thead> <tbody> <tr>";

    while ($ligne = $results1->fetch(PDO::FETCH_OBJ)) {
        $raison = $ligne->Raison_sociale;
        $color = ($ligne->montantTotal < 0) ? 'red' : 'green';
        echo"<td> $siren </td>
     <td> $ligne->Raison_sociale </td>
     <td> $ligne->nb </td>
     <td style='color:$color'> $ligne->montantTotal </td>
    ";
    }

$results = $dbh -> query("SELECT c.SIREN, Raison_sociale, Montant, Statut, DATE_VENTE FROM banque_clients c, banque_transaction WHERE DATE_VENTE = '$date' AND c.SIREN = '$siren'");

echo" </tr> </tbody></table>
    <table id='example' class='table table-striped ex' style='width:100%'>
    <thead>
         <th> N° de Siren </th>
        <th> Raison Sociale </th>
        <th> Montant (EUR) </th>
        <th> Statut </th>
        <th> Date transaction</th>
    </thead>
    <tbody>  
 ";

while ($ligne = $results->fetch(PDO::FETCH_OBJ)) {
    if ($ligne->Statut == 1) {
        $statut = 'payé';
        $color = "green";
    }
    else{
        $statut = 'impayé';
        $color = "red";
    }
    echo"
        <tr>
        <td> $siren </td>
        <td> $ligne->Raison_sociale</td>
        <td style='color:$color;'> $ligne->Montant</td>
        <td style='color:$color;'> $statut</td>
        <td> $date </td>
        </tr>
     ";
}
    echo "</tbody> </table>
<div class = 'export'>
<button id='downloadexcel'> Export vers excel</button>
<script>
document.getElementById('downloadexcel').addEventListener('click', function() {
      var table2excel = new Table2Excel();
  table2excel.export(document.querySelectorAll('#example'));
})
</script>

<button id='btnExportToCsv'>Exporter vers CSV </button>
    <script>
        const dataTable = document.getElementById('example');
        const btnExportToCsv = document.getElementById('btnExportToCsv');

        btnExportToCsv.addEventListener('click', () => {
            const exporter = new TableCSVExporter(dataTable);
            const csvOutput = exporter.convertToCSV();
            const csvBlob = new Blob([csvOutput], { type: 'text/csv' });
            const blobUrl = URL.createObjectURL(csvBlob);
            const anchorElement = document.createElement('a');

            anchorElement.href = blobUrl;
            anchorElement.download = 'DétailTransac.csv';
            anchorElement.click();

            setTimeout(() => {
                URL.revokeObjectURL(blobUrl);
            }, 500);
        });
    </script>

<button id='pdf' onclick='exportPdf()'> Exporter vers pdf</button>

<script>
document.getElementById('pdf').addEventListener('click', function() {
  // Create a new jsPDF instance
  var doc = new jsPDF();

  // Add content from the table to the PDF
  doc.autoTable({ html: '#example' });

  // Save the PDF with a specified name
  doc.save('table_to_pdf.pdf');
});
</script>
</div>


";

}
?>
