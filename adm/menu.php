<link rel="stylesheet" href="styles.css">
<?php echo "
<body>
<div class='menu'>
    <ul class='menu-list'>
            <li><img class ='menu-logo' src='img/logo.png' alt='Logo'></li>
";

$menuItems = array(
    'Supression' => 'admin_acc.php',
    'Deconnexion' => 'compte.php'
);

foreach ($menuItems as $label => $link) {
    echo "<li><a href=\"$link\">$label</a></li>";
}

echo"
    </ul>
</div>
</body>

";

?>

