<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../logica/paginaFuncties.php';

$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);

maakHead();
maakHeader("Inloggen als Klant");
?>

    <body>
    <?php if ($error_message): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>
    <form action="../logica/gebruikersFuncties.php" method="post">
        <input type="hidden" name="action" value="login">
        <input type="hidden" name="role" value="Client">

        <label for="username">Gebruikersnaam:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Wachtwoord:</label><br>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Inloggen</button>
    </form>
    <p>
        Heb je nog geen account?
        <a href="klantRegistratie.php">Klik hier om je te registreren</a>.
    </p>
    <p>
        Ben je medewerker?
        <a href="medewerkerLogin.php">Klik hier om in te loggen als medewerker</a>.
    </p>
    </body>

<?php maakFooter(); ?>