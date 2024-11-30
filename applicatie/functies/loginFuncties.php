<?php
session_start();
require '../db_connectie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'klant'; // Standaardwaarde is 'klant'

    try {
        $db = maakVerbinding();

        $stmt = $db->prepare("
            SELECT * FROM [User] WHERE username = ? AND role = ?
        ");
        $stmt->execute([$username, $role]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['role'] = $user['role'];

            echo "Inloggen succesvol! Welkom, " . htmlspecialchars($user['first_name']) . ".";
            if ($role === 'klant') {
                echo '<br><a href="../paginas/menu.php">Ga naar het menu</a>';
            } else {
                echo '<br><a href="../paginas/beheer.php">Ga naar het beheerpaneel</a>';
            }
        } else {
            echo "Onjuiste gebruikersnaam of wachtwoord.";
            echo '<br><a href="../paginas/' . ($role === 'klant' ? 'klantLogin.php' : 'medewerkerLogin.php') . '">Probeer opnieuw</a>';
        }
    } catch (Exception $e) {
        die("Fout bij het inloggen: " . $e->getMessage());
    }
} else {
    die("Ongeldig verzoek.");
}
?>