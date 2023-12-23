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
session_start();

// Vérifier si la session est déjà active
if (!isset($_SESSION['login']) || !isset($_SESSION['mdp'])) {
    // Si non connecté, vérifier les valeurs du formulaire
    if (isset($_POST['login']) && isset($_POST['mdp'])) {
        $login = $_POST['login'];
        $mdp = $_POST['mdp'];

        // Vérifier les identifiants
        if ($login == 'po77' && $mdp == 'po9377') {
            // Enregistrez les informations dans la session
            $_SESSION['login'] = $login;
            $_SESSION['mdp'] = $mdp;
        } else {
            echo "Connexion échouée";
        }
    }
}
?>

<?php
include 'menu.php';
require_once('confbdd.php') ;
// -------------------
// 1.Recherche par Siren et date de transaction
//  ------------------- 
echo "
<div class='box'>
    <h1>Annonce de trésorerie par date</h1>
    <center>
        <form action='tresorerie_po.php' method='post'>";
echo"
            <br>
            <label for='date'> Date de transaction</label>
            <input type='date' id='date' name='date' required>
            <br>
            <input type='submit' value='Valider' name='search1'>
        </form>
    </center>
</div>";
// -------------------
// 2.Recherche par Siren et date de transaction
// -------------------

echo "
<div class='box'>
    <h1>Rechercher Client Par SIREN</h1>
    <center>
        <form action='tresorerie_po.php' method='post'>
            <label for='siren'> SIREN </label>";

// Récupérer les données de la table banque_clients depuis confbdd.php
$results = $dbh->query("SELECT SIREN FROM banque_clients");

// Vérifier s'il y a des résultats
if ($results->rowCount() > 0) {
    echo "<select id='siren' name='siren' required>";
    while ($ligne = $results->fetch(PDO::FETCH_OBJ)) {
       $option = $ligne->SIREN;
       echo "<option value='$option'> $option </option>";
    }
    echo "</select>";
}

echo "
            <br>
            <label for='date'> Date de transaction</label>
            <input type='date' id='date' name='date' required>
            <br>
            <input type='submit' value='Valider' name='search2'>
        </form>
    </center>
</div>";
/*
// -------------------
// 3 Recherche par Siren et raison sociale
// -------------------
echo "
<div class='box'>
    <h1>Rechercher Client Par Raison sociale</h1>
    <center>
        <form action='tresorerie_po.php' method='post'>
            <label for='raison'> Raison Sociale </label>";

// Récupérer les données de la table banque_clients depuis confbdd.php
$results = $dbh->query("SELECT Raison_sociale FROM banque_clients");

// Vérifier s'il y a des résultats
if ($results->rowCount() > 0) {
    echo "<select id='raison' name='raison' required>";
    while ($ligne = $results->fetch(PDO::FETCH_OBJ)) {
       $option = $ligne->Raison_sociale;
       echo "<option value='$option'> $option </option>";
    }
    echo "</select>";
}

echo "
            <br>
            <label for='date'> Date de transaction</label>
            <input type='date' id='date' name='date' required>
            <br>
            <input type='submit' value='Valider' name='search3'>
        </form>
    </center>
</div>";
*/
//Tableau Trésorerie
if (isset($_POST['search1'])) {
    echo "<h1> Tableau des annonces</h1>";
    $date = $_POST['date'];
    $results1 = $dbh -> query("SELECT COUNT(*) AS nb, SUM(Montant) AS montantTotal
    FROM banque_clients c, banque_transaction
    WHERE DATE_VENTE = '$date'");
    echo"
    <table class='custom-table' style='width:100%'>
    <thead> 
        <th> N° Siren</th>
        <th> Raison Sociale</th>
        <th> Nombres Transactions</th>
        <th> Montant total</th>
    </thead> <tbody> <tr>";
    while ($ligne = $results1->fetch(PDO::FETCH_OBJ)) {

     echo"<td> / </td>
     <td> / </td>
     <td> $ligne->nb </td>";
     if ($ligne->montantTotal < 0){
         $color = 'red';
         echo"   <td style='color:$color;'> $ligne->montantTotal </td>";
    }
     else{
         $color = 'green';
         echo"   <td> $ligne->montantTotal </td>";
     }
    }
    echo" </tr> </tbody></table>";


    echo "<h1> Détail de trésorerie </h1>";
    $results = $dbh -> query("SELECT c.SIREN, Raison_sociale, Montant, Statut, DATE_VENTE FROM banque_clients c, banque_transaction WHERE DATE_VENTE = '$date'");
    echo "
    <table id='example' class='table table-striped custom-table' style='width:100%'>
    <thead>
    <tr>
        <th> N° de Siren </th>
        <th> Raison Sociale </th>
        <th> Montant (EUR) </th>
        <th> Statut </th>
        <th> Date transaction</th>
        </tr>
    </thead>
    <tbody> <div id='content'>";


        while ($ligne = $results->fetch(PDO::FETCH_OBJ)) {
            echo "<tr>";
           $siren = $ligne->SIREN;
           $rs = $ligne -> Raison_sociale;
           $montant = $ligne -> Montant;
           $stat = $ligne -> Statut;
           if ($stat == 1) {
            $statut = 'payé';
            $color = 'green';
           }
           else{
            $statut = 'impayé';
            $color = 'red';
           }
           echo"
           <td> $siren </td>
           <td> $rs </td>
           <td style='color:$color;'> $montant </td>
           <td style='color:$color;'> $statut </td>
           <td> $date </td>
           </tr>";
        }


    //EXPORT
    echo"</tbody></table></div>

<div class = 'export'>
<h3>Exporter le tableau des transactions</h3>
<button id='downloadexcel'> Export vers un fichier excel </button>
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

 /*




*
         * */

}

//Recherche par SIREN
if (isset ($_POST['search2'])){
    $siren = $_POST['siren'];
    $date= $_POST['date'];

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
     echo"<td> $siren </td>
     <td> $ligne->Raison_sociale </td>
     <td> $ligne->nb </td>";
          if ($ligne->montantTotal < 0){
         $color = 'red';
         echo"   <td style='color:$color;'> $ligne->montantTotal </td>";
    }
     else{
         $color = 'green';
         echo"   <td style='color:$color;'> $ligne->montantTotal </td>";
     }


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
            $color = 'green';
        }
        else{
            $statut = 'impayé';
            $color = 'red';

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


//RECHERCHE PAR RAISON SOCIALE
/*
if (isset ($_POST['search3'])){
    $raison = $_POST['raison'];
    $date= $_POST['date'];

    echo "<h1> Tableau des annonces</h1>";
    $date = $_POST['date'];
    $results1 = $dbh -> query("
SELECT COUNT(*) AS nb, SUM(Montant) AS montantTotal, c.SIREN, c.Raison_sociale
FROM banque_clients c
JOIN banque_transaction bt ON c.SIREN = bt.SIREN
WHERE bt.DATE_VENTE = '$date' AND c.Raison_sociale LIKE '%$raison%'
GROUP BY c.SIREN, c.Raison_sociale;
");
    echo"
    <table class='table table-striped' style='width:100%'>
    <thead> 
        <th> N° Siren</th>
        <th> Raison Sociale</th>
        <th> Nombres Transactions</th>
        <th> Montant total</th>
    </thead> <tbody> <tr>";

    while ($ligne = $results1->fetch(PDO::FETCH_OBJ)) {
        echo"<td> $ligne->SIREN </td>
     <td>  $raison</td>
     <td> $ligne->nb </td>

     <td> $ligne->montantTotal </td>";

    }
    echo "</tr> </tbody></table>";
    $results = $dbh -> query("SELECT c.SIREN, Raison_sociale, Montant, Statut, DATE_VENTE FROM banque_clients c, banque_transaction WHERE DATE_VENTE = '$date' AND c.Raison_sociale = '$raison'");

    echo" 
    <h1> Détails des transactions </h1>
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
        }
        else{
            $statut = 'impayé';
        }
        echo"
        <tr>
        <td> $ligne->SIREN </td>
        <td> $raison</td>
        <td> $ligne->Montant</td>
        <td> $statut</td>
        <td> $date </td>
        </tr>
     ";
    }


    echo
    "</tbody> </table>

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

}*/

?>