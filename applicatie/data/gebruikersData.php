<?php
require_once 'db_connectie.php';


function haalGebruikerOp($username, $role) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("
            SELECT username, password, role 
            FROM [User] 
            WHERE username = ? AND role = ?
        ");
        $stmt->execute([$username, $role]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Fout bij ophalen van gebruiker: " . $e->getMessage());
    }
}

function updateWachtwoord($username, $hashedPassword) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("UPDATE [User] SET password = ? WHERE username = ?");
        $stmt->execute([$hashedPassword, $username]);
    } catch (Exception $e) {
        throw new Exception("Fout bij updaten van wachtwoord: " . $e->getMessage());
    }
}


function updateAlleWachtwoorden() {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("SELECT username, password FROM [User]");
        $stmt->execute();
        $gebruikers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($gebruikers as $gebruiker) {
            if (password_get_info($gebruiker['password'])['algo'] === 0) {
                $hashedPassword = password_hash($gebruiker['password'], PASSWORD_DEFAULT);
                updateWachtwoord($gebruiker['username'], $hashedPassword);
            }
        }
    } catch (Exception $e) {
        throw new Exception("Fout bij updaten van wachtwoorden: " . $e->getMessage());
    }
}