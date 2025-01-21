<?php
require_once '../logica/paginaFuncties.php';
require_once '../data/gebruikersData.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'Client';

    try {
        $gebruiker = haalGebruikerOp($username, $role);

        if ($gebruiker && password_verify($password, $gebruiker['password'])) {
            session_start();
            $_SESSION['username'] = $gebruiker['username'];
            $_SESSION['role'] = $gebruiker['role'];

            header("Location: menu.php");
            exit;
        } else {
            header("Location: klantLogin.php?error=invalid_credentials");
            exit;
        }
    } catch (Exception $e) {
        header("Location: klantLogin.php?error=invalid_credentials");
        exit;
    }
}

maakHead();
maakHeader("Inloggen als Klant");
?>

<body>
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