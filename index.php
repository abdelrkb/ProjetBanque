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
    <h2 class="pc">Se connecter</h2>

    <form action="" method="post">
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
        <a href="indexpo.php"> Connexion admin</a>
</form>

</div>


<?php
if($_POST) {
    require_once('confbdd.php') ;

    session_start();
    $login=$_POST['login'];
    $mdp=$_POST['mdp'];

    $ok = false;

    $results=$dbh->query("SELECT login FROM banque_clients");
    while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
        if ($ligne->login == $login){
            $ok = true;
        }
    }

    if ($ok){
        $results=$dbh->query("SELECT login FROM banque_clients where login = '$login'");
        $ligne = $results->fetch(PDO::FETCH_OBJ);
        if ($ligne->login == ($mdp)){
            $_SESSION['login'] = $login;
            $_SESSION['mdp'] = $mdp;
            header('Location: compte.php');
            exit();
        }else{
            echo "Authentification ratée";
        }
    }else{
        echo "Login inexistant";
    }
    $results->closeCursor();
}
?>
</html>