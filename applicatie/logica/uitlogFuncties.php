<?php
// Controleer of er al een sessie actief is en start deze zo nodig
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vernietig alle sessiegegevens
$_SESSION = [];

// Verwijder de sessiecookie als deze bestaat
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Vernietig de sessie
session_destroy();

// Redirect naar de loginpagina met een succesmelding
header("Location: ../presentatie/klantLogin.php?logout=success");
exit;