<?php
session_start();
session_unset();
session_destroy();
// Supprimer le cookie de session en fixant sa date d'expiration à une date passée
if (isset( $_COOKIE['siren_cookie'])) {
    setcookie('siren_cookie', '', time() - 3600, '/');
}

header ('location: ../index.php');

?>