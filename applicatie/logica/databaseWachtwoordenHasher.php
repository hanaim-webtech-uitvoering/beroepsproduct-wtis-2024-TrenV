<?php
require 'db_connectie.php';

try {
    $db = maakVerbinding();

    $stmt = $db->prepare("SELECT username, password FROM [User]");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        $username = $user['username'];
        $password = $user['password'];

        if (password_get_info($password)['algo'] === 0) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $updateStmt = $db->prepare("UPDATE [User] SET password = ? WHERE username = ?");
            $updateStmt->execute([$hashedPassword, $username]);

            echo "Wachtwoord voor gebruiker $username geüpdatet.<br>";
        } else {
            echo "Wachtwoord voor gebruiker $username is al gehashed.<br>";
        }
    }

    echo "Controle en update van wachtwoorden voltooid.";
} catch (Exception $e) {
    die("Fout bij het verwerken van wachtwoorden: " . $e->getMessage());
}
?>