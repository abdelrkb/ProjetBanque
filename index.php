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
    <div class="screen">

        <div class="screen__content">
            <h1 style="text-align: left;">Client</h1>

            <form class="login" action="client/tresorerie_client.php">
                <div class="login__field">
                    <i class="login__icon fas fa-user"></i>
                    <input type="text" class="login__input" placeholder="Identifiant" id="login" name="login">
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" placeholder="Password" id="mdp" name="mdp">
                    <span class="toggle-password" onclick="togglePassword()">👁</span>
                </div>
                <button class="button login__submit">
                    <span class="button__text">Se connecter</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
            </form>

        </div>
        <div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
            <span class="screen__background__shape screen__background__shape3"></span>
            <span class="screen__background__shape screen__background__shape2"></span>
            <span class="screen__background__shape screen__background__shape1"></span>

        </div>
        <button class="button login__submit" onclick="redirect1()">
        <a href="indexpo.php"> Connexion po</a>
        </button>
        <button class="button login__submit" onclick="redirect2()">
        <a href="indexadmin.php"> Connexion admin</a>
        </button>
    </div>

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

<script>
    function togglePassword() {
        var passwordInput = document.getElementById("mdp");
        var toggleIcon = document.querySelector(".toggle-password");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.textContent = "👁️"; // Œil ouvert
        } else {
            passwordInput.type = "password";
            toggleIcon.textContent = "👁️"; // Œil fermé
        }
    }
    function redirect1() {
        // Remplacez 'nouvelle_page.html' par le chemin de la page vers laquelle vous souhaitez rediriger
        window.location.href = 'indexpo.php';
    }

    function redirect2() {
        // Remplacez 'nouvelle_page.html' par le chemin de la page vers laquelle vous souhaitez rediriger
        window.location.href = 'indexadmin.php';
    }

</script>

</html>