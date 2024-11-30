<?php
session_start();
require '../functies/paginaFuncties.php';

maakHead();
maakHeader("Registreren als klant");
?>

    <body>
    <form action="../functies/registreerFuncties.php" method="post">
        <input type="hidden" name="action" value="register">
        <input type="hidden" name="role" value="klant">

        <label for="username">Gebruikersnaam:</label><br>
        <input type="text" id="username" name="username" placeholder="Gebruikersnaam" required><br><br>

        <label for="password">Wachtwoord:</label><br>
        <input type="password" id="password" name="password" placeholder="Wachtwoord" required><br><br>

        <label for="first_name">Voornaam:</label><br>
        <input type="text" id="first_name" name="first_name" placeholder="Voornaam" required><br><br>

        <label for="last_name">Achternaam:</label><br>
        <input type="text" id="last_name" name="last_name" placeholder="Achternaam" required><br><br>

        <label for="address">Adres:</label><br>
        <input type="text" id="address" name="address" placeholder="Adres" required><br><br>

        <button type="submit">Registreren</button>
    </form>
    </body>

<?php maakFooter(); ?>