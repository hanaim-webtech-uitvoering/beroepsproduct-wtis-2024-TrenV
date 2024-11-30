<?php
require '../functies/paginaFuncties.php';

maakHead();
maakHeader("Welkom bij Pizzeria Sole Machina 🍕");
?>

    <body>

    <h2>Kies een optie:</h2>

    <h3>Klanten</h3>
    <form action="klantRegistratie.php" method="get">
        <button type="submit">Registreren als Klant</button>
    </form>
    <form action="klantLogin.php" method="get">
        <button type="submit">Inloggen als Klant</button>
    </form>

    <br>

    <h3>Medewerkers</h3>
    <form action="medewerkerLogin.php" method="get">
        <button type="submit">Inloggen als Medewerker</button>
    </form>
    </body>

<?php maakFooter(); ?>