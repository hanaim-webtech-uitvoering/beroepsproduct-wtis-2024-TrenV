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

function haalKlantGegevensOp($username) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("
            SELECT first_name, last_name, address 
            FROM [User] 
            WHERE username = ?
        ");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Fout bij ophalen van klantgegevens: " . $e->getMessage());
    }
}

function haalBestellingenVanKlant($clientUsername) {
    try {
        $db = maakVerbinding();

        $stmt = $db->prepare("
            SELECT 
                po.order_id, 
                po.datetime, 
                po.status,
                STRING_AGG(CONCAT(pop.product_name, ' (', pop.quantity, ')'), ', ') AS producten
            FROM 
                Pizza_Order po
            LEFT JOIN 
                Pizza_Order_Product pop ON po.order_id = pop.order_id
            WHERE 
                po.client_name = ?
            GROUP BY 
                po.order_id, po.datetime, po.status
            ORDER BY 
                po.datetime DESC
        ");
        $stmt->execute([$clientUsername]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Fout bij ophalen van bestellingen van klant: " . $e->getMessage());
    }
}

function updateKlantGegevens($username, $firstName, $lastName, $address) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("
            UPDATE [User]
            SET first_name = ?, last_name = ?, address = ?
            WHERE username = ?
        ");
        $stmt->execute([$firstName, $lastName, $address, $username]);
    } catch (Exception $e) {
        throw new Exception("Fout bij bijwerken van klantgegevens: " . $e->getMessage());
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
?>