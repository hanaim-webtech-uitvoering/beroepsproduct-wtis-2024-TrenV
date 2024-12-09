<?php
require_once '../helpers/paginaFuncties.php';
require_once '../data/gebruikersData.php'; // Voor database-interacties

// Controleer of het formulier correct is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'Client'; // Standaardrol is 'Client'

    // Haal gebruiker op via de data-laag
    try {
        $gebruiker = haalGebruikerOp($username, $role); // Functie uit gebruikersData.php

        // Controleer wachtwoord
        if ($gebruiker && password_verify($password, $gebruiker['password'])) {
            // Sessie instellen
            session_start();
            $_SESSION['username'] = $gebruiker['username'];
            $_SESSION['role'] = $gebruiker['role'];

            // Redirect naar menu
            header("Location: menu.php");
            exit;
        } else {
            header("Location: klantLogin.php?error=invalid_credentials");
            exit;
        }
    } catch (Exception $e) {
        // Log de fout en toon een generieke foutmelding
        header("Location: klantLogin.php?error=invalid_credentials");
        exit;
    }
}

maakHead();
maakHeader("Inloggen als Klant");
?>

<body>
    <h2>Inloggen als Klant</h2>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
        <p style="color: red;">Ongeldige gebruikersnaam of wachtwoord. Probeer opnieuw.</p>
    <?php endif; ?>
    <form action="klantLogin.php" method="post">
        <label for="username">Gebruikersnaam:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Wachtwoord:</label><br>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Inloggen</button>
    </form>
</body>

<?php maakFooter(); ?>