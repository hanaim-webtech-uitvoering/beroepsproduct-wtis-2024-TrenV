<?php
session_start();
require '../functies/paginaFuncties.php';

maakHead();
maakHeader("Inloggen als Klant");
?>

    <body>
    <form action="../functies/loginFuncties.php" method="post">
        <input type="hidden" name="action" value="login">
        <input type="hidden" name="role" value="klant">

        <label for="username">Gebruikersnaam:</label><br>
        <input type="text" id="username" name="username" placeholder="Gebruikersnaam" required><br><br>

        <label for="password">Wachtwoord:</label><br>
        <input type="password" id="password" name="password" placeholder="Wachtwoord" required><br><br>

        <button type="submit">Inloggen</button>
    </form>
    </body>

<?php maakFooter(); ?>