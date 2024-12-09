<?php
session_start();
require '../helpers/paginaFuncties.php';
maakHead();
maakHeader("Welkom bij Pizzeria Sole Machina");
?>

<body>
    <p>Bestel de beste pizza's in de stad!</p>
    <a href="menu.php">Bekijk ons menu</a>
</body>

<?php maakFooter(); ?>