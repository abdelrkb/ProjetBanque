<link rel="stylesheet" href="styles.css">

<?php echo "
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
</body>

";

        ?>