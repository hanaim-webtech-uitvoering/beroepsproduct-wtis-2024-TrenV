<?php
session_start();
require '../db_connectie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'klant'; // Standaardwaarde is 'klant'

    try {
        $db = maakVerbinding();

        // Controleer de rol
        $roleValue = ($role === 'medewerker') ? 'Personnel' : 'klant';

        // Haal gebruiker op uit de database
        $stmt = $db->prepare("
            SELECT * FROM [User] 
            WHERE username = ? 
            AND role = ?
        ");
        $stmt->execute([$username, $roleValue]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Controleer gebruikersnaam en wachtwoord
        if ($user && $password === $user['password']) { // Geen password_verify
            // Sla sessie-informatie op
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['role'] = $user['role'];

            // Redirect op basis van rol
            if ($role === 'klant') {
                header("Location: ../paginas/menu.php");
                exit;
            } else if ($role === 'medewerker') {
                header("Location: ../paginas/bestelOverzicht.php");
                exit;
            } else {
                die("Ongeldige gebruikersrol.");
            }
        } else {
            // Foutieve inloggegevens
            $redirectPage = $role === 'klant' ? 'klantLogin.php' : 'medewerkerLogin.php';
            die("Onjuiste gebruikersnaam of wachtwoord. <br><a href='../paginas/$redirectPage'>Probeer opnieuw</a>");
        }
    } catch (Exception $e) {
        die("Fout bij het inloggen: " . $e->getMessage());
    }
} else {
    die("Ongeldig verzoek.");
}
?>