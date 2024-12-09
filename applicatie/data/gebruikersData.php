<?php
require_once 'db_connectie.php';

// Haal een gebruiker op basis van username en rol
function haalGebruikerOp($username, $role) {
    try {
        $db = maakVerbinding();

        $stmt = $db->prepare("
            SELECT * FROM [User]
            WHERE username = ? AND role = ?
        ");
        $stmt->execute([$username, $role]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Fout bij ophalen van gebruiker: " . $e->getMessage());
    }
}

// Controleer of een gebruikersnaam al bestaat
function gebruikersnaamBestaat($username) {
    try {
        $db = maakVerbinding();

        $stmt = $db->prepare("SELECT COUNT(*) FROM [User] WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    } catch (Exception $e) {
        throw new Exception("Fout bij controleren van gebruikersnaam: " . $e->getMessage());
    }
}

// Voeg een nieuwe gebruiker toe
function voegGebruikerToe($username, $hashedPassword, $firstName, $lastName, $address, $role) {
    try {
        $db = maakVerbinding();

        $stmt = $db->prepare("
            INSERT INTO [User] (username, password, first_name, last_name, address, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $hashedPassword, $firstName, $lastName, $address, $role]);
    } catch (Exception $e) {
        throw new Exception("Fout bij toevoegen van gebruiker: " . $e->getMessage());
    }
}

// Update een wachtwoord
function updateWachtwoord($username, $hashedPassword) {
    try {
        $db = maakVerbinding();

        $stmt = $db->prepare("UPDATE [User] SET password = ? WHERE username = ?");
        $stmt->execute([$hashedPassword, $username]);
    } catch (Exception $e) {
        throw new Exception("Fout bij updaten van wachtwoord: " . $e->getMessage());
    }
}

// Haal alle gebruikers op
function haalAlleGebruikersOp() {
    try {
        $db = maakVerbinding();

        $stmt = $db->prepare("SELECT username, password FROM [User]");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Fout bij ophalen van gebruikers: " . $e->getMessage());
    }
}
?>