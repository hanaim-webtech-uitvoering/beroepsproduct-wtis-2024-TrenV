<?php
require 'db_connectie.php';

try {
    $db = maakVerbinding();

    // Haal alle records op uit de tabel
    $stmt = $db->prepare("SELECT username, password FROM [User]");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop door alle gebruikers
    foreach ($users as $user) {
        $username = $user['username'];
        $password = $user['password'];

        // Controleer of het wachtwoord al gehashed is
        if (password_get_info($password)['algo'] === 0) {
            // Het wachtwoord is nog niet gehashed
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Update het gehashte wachtwoord in de database
            $updateStmt = $db->prepare("UPDATE [User] SET password = ? WHERE username = ?");
            $updateStmt->execute([$hashedPassword, $username]);

            echo "Wachtwoord voor gebruiker $username geüpdatet.<br>";
        } else {
            // Het wachtwoord is al gehashed
            echo "Wachtwoord voor gebruiker $username is al gehashed.<br>";
        }
    }

    echo "Controle en update van wachtwoorden voltooid.";
} catch (Exception $e) {
    die("Fout bij het verwerken van wachtwoorden: " . $e->getMessage());
}
?>