<link rel="stylesheet" href="styles.css">
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
echo "
<body>
    <div class='menu'>
        <ul>";

                $menuItems = array(
                    'Tresorerie' => 'tresorerie_po.php',
                    'Remise' => 'remise_po.php',
                    'Impaye' => 'impaye_po.php'
                );

                foreach ($menuItems as $label => $link) {
                    echo "<li><a href=\"$link\">$label</a></li>";
                }

echo"                
        </ul>
    </div>
</body>";
?>