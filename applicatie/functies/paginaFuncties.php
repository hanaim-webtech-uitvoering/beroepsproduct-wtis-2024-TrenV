<?php
function maakHead() {
    echo <<<HTML
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzeria Sole Machina 🍕</title>
</head>
HTML;
}

function maakHeader($pagina){
    echo <<<HTML
<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="profiel.php">Profiel</a></li>
HTML;

    // Voeg een knop toe om in of uit te loggen, afhankelijk van de sessiestatus
    if (isset($_SESSION['username'])) {
        echo '<li><a href="../functies/logout.php">Uitloggen</a></li>';
    } else {
        echo '<li><a href="klantLogin.php">Inloggen</a></li>';
    }

    echo <<<HTML
        </ul>
    </nav>
    <h1>$pagina</h1>
</header>
HTML;
}

function maakFooter(){
    echo <<<HTML
<footer>
    <p>&copy; 2024 Pizzeria Sole Machina 🍕</p>
</footer>
HTML;
}
?>