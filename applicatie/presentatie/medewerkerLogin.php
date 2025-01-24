<?php
require_once '../logica/paginaFuncties.php';

maakHead();
maakHeader("Inloggen als Medewerker");
?>

    <body>
    <form action="../logica/gebruikersFuncties.php" method="post">
        <input type="hidden" name="action" value="login">
        <input type="hidden" name="role" value="Personnel">

        <label for="username">Gebruikersnaam:</label><br>
        <input type="text" id="username" name="username" placeholder="Gebruikersnaam" required><br><br>

        <label for="password">Wachtwoord:</label><br>
        <input type="password" id="password" name="password" placeholder="Wachtwoord" required><br><br>

        <button type="submit">Inloggen</button>
    </form>
    <p>
        Niet de juiste pagina? Ga terug naar de <a href="menu.php">homepage</a>.
    </p>
    </body>

<?php maakFooter(); ?>