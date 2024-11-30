<?php
require '../db_connectie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'register') {
    $username = $_POST['username'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $role = $_POST['role'] ?? 'klant'; // Standaardwaarde is 'klant'

    if (empty($username) || empty($password) || empty($first_name) || empty($last_name) || empty($address)) {
        die("Alle velden moeten worden ingevuld.");
    }

    try {
        $db = maakVerbinding();

        $stmt = $db->prepare("
            INSERT INTO [User] (username, password, first_name, last_name, address, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $password, $first_name, $last_name, $address, $role]);

        echo "Registratie succesvol! U kunt nu inloggen.<br>";
        if ($role === 'klant') {
            echo '<a href="../paginas/klantLogin.php">Inloggen als Klant</a>';
        } else {
            echo '<a href="../paginas/medewerkerLogin.php">Inloggen als Medewerker</a>';
        }
    } catch (Exception $e) {
        die("Fout bij het registreren: " . $e->getMessage());
    }
} else {
    die("Ongeldig verzoek.");
}
?>