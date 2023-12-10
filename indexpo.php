<?php
$loginadm = 2001458436;
$loginpo = 4526452419;
?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>


<?php
session_start();
if (isset($_SESSION['login']) && isset($_SESSION['mdp'])){
    header('Location: compte.php');
    exit();
}
?>


<div class="container">
    <h2 class="pc">Connexion PO</h2>

    <form action="po/tresorerie_po.php" method="post">
        <ul>
            <li>
                <label for="login">Login: </label>
                <input type="text" id="login" name="login" />
            </li>
            <li>
                <label for="mdp">Mot de passe: </label>
                <input type="password" id="mdp" name="mdp" />
            </li>
            <div class="button">
                <button type="submit">Se connecter</button>
            </div>
        </ul>
    </form>

</div>