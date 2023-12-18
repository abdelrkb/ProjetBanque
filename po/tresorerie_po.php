<link rel="stylesheet" href="styles.css">
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="script.js"></script>

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
    } else {
        // Rediriger vers la page de connexion si aucune session ni données de formulaire
        header('Location: indexpo.php');
        exit();
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
        <form action='tresorerie_po.php' method='post'>
            <label for='siren'> SIREN </label>

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

//Tableau Trésorerie
if (isset($_POST['search1'])) {
    echo "<h1> Tableau des annonces</h1>";
    $date = $_POST['date'];
    $results1 = $dbh -> query("SELECT COUNT(*) AS nb, SUM(Montant) AS montantTotal
    FROM banque_clients c, banque_transaction
    WHERE DATE_VENTE = '$date'");
    echo"
    <table id='example' class='table table-striped' style='width:100%'>
    <thead> 
        <th> N° Siren</th>
        <th> Raison Sociale</th>
        <th> Nombres Transactions</th>
        <th> Devise (EUR)</th>
        <th> Montant total</th>
    </thead> <tbody> <tr>";
    
    while ($ligne = $results1->fetch(PDO::FETCH_OBJ)) {
     echo"<td> / </td>
     <td> / </td>
     <td> $ligne->nb </td>
     <td> '€' </td>
     <td> $ligne->montantTotal </td>
     ";
    }
    echo" </tr> </tbody></table>";


    echo "<h1> Détail de trésorerie </h1>";
    $results = $dbh -> query("SELECT c.SIREN, Raison_sociale, Montant, Statut, DATE_VENTE FROM banque_clients c, banque_transaction WHERE DATE_VENTE = '$date'");  
    echo "
    <table id='example' class='table table-striped' style='width:100%'>
    <thead>
        <th> N° de Siren </th>
        <th> Raison Sociale </th>
        <th> Montant (EUR) </th>
        <th> Statut </th>
        <th> Date transaction</th>
    </thead>
    <tbody>";
    
        
        while ($ligne = $results->fetch(PDO::FETCH_OBJ)) {
            echo "<tr>";
           $siren = $ligne->SIREN;
           $rs = $ligne -> Raison_sociale;
           $montant = $ligne -> Montant;
           $stat = $ligne -> Statut;
           if ($stat == 1) {
            $statut = 'payé';
           }
           else{
            $statut = 'impayé';
           }
           echo"
           <td> $siren </td>
           <td> $rs </td>
           <td> $montant </td>
           <td> $statut </td>
           <td> $date </td>
           </tr>";
        }
        
    
    //EXPORT
    echo"</tbody></table>

    <script src='https//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js'></script>
      <script src='https//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js'></script>
    <button id ='exp'> Exporter vers excel </button>
    <script type='text/javascript'>
    $(#'exp').click(function(
        $('#example').table2excel({
    name: 'Détails des transactions du $date',
    filename: 'DetailTransac.xls', // do include extension
    preserveColors: false // set to true if you want background colors and font colors preserved
});
        
    ));
</script>
";

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
    <table id='example' class='table table-striped' style='width:100%'>
    <thead> 
        <th> N° Siren</th>
        <th> Raison Sociale</th>
        <th> Nombres Transactions</th>
        <th> Devise (EUR)</th>
        <th> Montant total</th>
    </thead> <tbody> <tr>";
    
    while ($ligne = $results1->fetch(PDO::FETCH_OBJ)) {
        $raison = $ligne->Raison_sociale;
     echo"<td> $siren </td>
     <td> $ligne->Raison_sociale </td>
     <td> $ligne->nb </td>
     <td> '€' </td>
     <td> $ligne->montantTotal </td>
     ";
    }

    $results = $dbh -> query("SELECT c.SIREN, Raison_sociale, Montant, Statut, DATE_VENTE FROM banque_clients c, banque_transaction WHERE DATE_VENTE = '$date' AND c.SIREN = '$siren'");

    echo" </tr> </tbody></table>
    <table id='example' class='table table-striped' style='width:100%'>
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
        <td> $siren </td>
        <td> $ligne->Raison_sociale</td>
        <td> $ligne->Montant</td>
        <td> $statut</td>
        <td> $date </td>
        </tr>
     ";
    }


    echo "</tbody> </table>";

}

if (isset ($_POST['search3'])){
    $raison = $_POST['raison'];
    $date= $_POST['date'];

    echo "<h1> Tableau des annonces</h1>";
    $date = $_POST['date'];
    $results1 = $dbh -> query("
SELECT COUNT(*) AS nb, SUM(Montant) AS montantTotal, c.SIREN, c.Raison_sociale
FROM banque_clients c
JOIN banque_transaction ON c.SIREN = banque_transaction.SIREN
WHERE DATE_VENTE = '$date' AND c.Raison_sociale = '$raison'
GROUP BY c.SIREN, c.Raison_sociale;

");
    echo"
    <table id='example' class='table table-striped' style='width:100%'>
    <thead> 
        <th> N° Siren</th>
        <th> Raison Sociale</th>
        <th> Nombres Transactions</th>
        <th> Devise (EUR)</th>
        <th> Montant total</th>
    </thead> <tbody> <tr>";

    while ($ligne = $results1->fetch(PDO::FETCH_OBJ)) {
        echo"<td> $ligne->SIREN </td>
     <td>  $raison</td>
     <td> $ligne->nb </td>
     <td> '€' </td>
     <td> $ligne->montantTotal </td>
     ";
    }
    echo "</tr> </tbody></table>";
    $results = $dbh -> query("SELECT c.SIREN, Raison_sociale, Montant, Statut, DATE_VENTE FROM banque_clients c, banque_transaction WHERE DATE_VENTE = '$date' AND c.Raison_sociale = '$raison'");

    echo" 
    <h1> Détails des transactions </h1>
    <table id='example' class='table table-striped' style='width:100%'>
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


    echo "</tbody> </table>";

}

?>