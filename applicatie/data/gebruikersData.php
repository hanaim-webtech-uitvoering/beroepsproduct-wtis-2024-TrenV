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

function registreerNieuweGebruiker($username, $password, $firstName, $lastName, $address, $role) {
    try {
        $db = maakVerbinding();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("
            INSERT INTO [User] (username, password, first_name, last_name, address, role) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $hashedPassword, $firstName, $lastName, $address, $role]);
    } catch (Exception $e) {
        throw new Exception("Fout bij registreren van gebruiker: " . $e->getMessage());
    }
}
?>