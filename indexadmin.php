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
    <div class="screen">

        <div class="screen__content">
            <h1 style="text-align: left;">Admin</h1>

            <form class="login" action="adm/admin_acc.php">
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
        <button class="button login__submit" onclick="redirect()">
            <a href="index.php"> Retour </a>
        </button>
    </div>

</div>

</div>

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

    function redirect() {
        // Remplacez 'nouvelle_page.html' par le chemin de la page vers laquelle vous souhaitez rediriger
        window.location.href = 'index.php';
    }
</script>