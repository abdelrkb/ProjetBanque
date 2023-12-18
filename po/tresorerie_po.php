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
            <label for='siren'> SIREN </label>";

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

if (isset($_POST['search3'])) {
    echo "
    <table id='example' class='table table-striped' style='width:100%'>
    <thead>
        <th> N° de Siren </th>
        <th>             </th>

    </thead>
    ";
}


?>