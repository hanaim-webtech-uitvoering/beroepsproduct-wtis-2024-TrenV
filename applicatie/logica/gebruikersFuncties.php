<?php
require_once '../data/gebruikersData.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    switch ($action) {
        case 'login':
            handleLogin();
            break;
        case 'register':
            handleRegister();
            break;
        default:
            die("Ongeldige actie.");
    }
} else {
    die("Ongeldig verzoek.");
}

function handleLogin() {
    if (!isset($_POST['username'], $_POST['password'], $_POST['role'])) {
        die("Vul alle velden in.");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    try {
        $gebruiker = haalGebruikerOp($username, $role);

        if ($gebruiker && password_verify($password, $gebruiker['password'])) {
            session_start();
            $_SESSION['username'] = $gebruiker['username'];
            $_SESSION['role'] = $gebruiker['role'];

            if ($role === 'Personnel') {
                header("Location: ../presentatie/bestelOverzicht.php");
            } else {
                header("Location: ../presentatie/menu.php");
            }
            exit;
        } else {
            header("Location: ../presentatie/medewerkerLogin.php?error=invalid_credentials");
            exit;
        }
    } catch (Exception $e) {
        header("Location: ../presentatie/medewerkerLogin.php?error=invalid_credentials");
        exit;
    }
}

function handleRegister() {
    if (!isset($_POST['username'], $_POST['password'], $_POST['first_name'], $_POST['last_name'], $_POST['address'], $_POST['role'])) {
        die("Vul alle velden in.");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    try {
        // Controleer of de gebruikersnaam al in gebruik is
        if (gebruikersnaamBestaat($username)) {
            throw new Exception("De gebruikersnaam is al in gebruik. Kies een andere.");
        }

        registreerNieuweGebruiker($username, $password, $firstName, $lastName, $address, $role);

        header("Location: ../presentatie/klantLogin.php?success=registered");
        exit;
    } catch (Exception $e) {
        header("Location: ../presentatie/klantRegistratie.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}
?>