<link rel="stylesheet" href="styles.css">
<?php echo "
<body>
<div class='menu'>
    <ul class='menu-list'>
            <li><img class ='menu-logo' src='img/logo.png' alt='Logo'></li>
";

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
</body>

";

?>

