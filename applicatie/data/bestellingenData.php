<?php
require_once 'db_connectie.php';

function voegBestellingToe($klantNaam, $adres, $producten) {
    try {
        $db = maakVerbinding();
        $db->beginTransaction();

        $stmtPersoneel = $db->prepare("SELECT TOP 1 username FROM [User] WHERE role = 'Personnel' ORDER BY NEWID()");
        $stmtPersoneel->execute();
        $personeelslid = $stmtPersoneel->fetch(PDO::FETCH_ASSOC);

        if (!$personeelslid) {
            throw new Exception("Geen beschikbaar personeelslid gevonden.");
        }

        $personeelUsername = $personeelslid['username'];

        $stmt = $db->prepare("
            INSERT INTO Pizza_Order (client_name, address, datetime, status, personnel_username) 
            VALUES (?, ?, GETDATE(), 0, ?)
        ");
        $stmt->execute([$klantNaam, $adres, $personeelUsername]);
        $orderId = $db->lastInsertId();

        // Voeg producten toe
        $stmtProduct = $db->prepare("
            INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) 
            VALUES (?, ?, ?)
        ");
        foreach ($producten as $product) {
            $stmtProduct->execute([$orderId, $product['name'], $product['quantity']]);
        }

        $db->commit();
        return $orderId;
    } catch (Exception $e) {
        $db->rollBack();
        throw new Exception("Fout bij het toevoegen van de bestelling: " . $e->getMessage());
    }
}

function haalAlleBestellingenOp() {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("
            SELECT 
                po.order_id, 
                po.client_name, 
                po.address, 
                po.datetime, 
                po.status, 
                po.personnel_username,
                STRING_AGG(CONCAT(pop.product_name, ' (', pop.quantity, ')'), ', ') AS producten
            FROM 
                Pizza_Order po
            LEFT JOIN 
                Pizza_Order_Product pop ON po.order_id = pop.order_id
            GROUP BY 
                po.order_id, po.client_name, po.address, po.datetime, po.status, po.personnel_username
            ORDER BY 
                po.datetime DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Fout bij het ophalen van bestellingen: " . $e->getMessage());
    }
}

function haalBestellingDetails($orderId) {
    try {
        $db = maakVerbinding();

        $stmt = $db->prepare("
            SELECT 
                po.order_id,
                po.client_name,
                po.address,
                po.datetime,
                po.status,
                po.personnel_username
            FROM 
                Pizza_Order po
            WHERE 
                po.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $bestelling = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$bestelling) {
            throw new Exception("Geen bestelling gevonden met ID: $orderId");
        }

        $stmtProducten = $db->prepare("
            SELECT 
                product_name AS name,
                quantity 
            FROM 
                Pizza_Order_Product 
            WHERE 
                order_id = ?
        ");
        $stmtProducten->execute([$orderId]);
        $bestelling['producten'] = $stmtProducten->fetchAll(PDO::FETCH_ASSOC);

        return $bestelling;
    } catch (Exception $e) {
        throw new Exception("Fout bij ophalen van bestellingdetails: " . $e->getMessage());
    }
}

function updateBestellingStatus($orderId, $status) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("UPDATE Pizza_Order SET status = ? WHERE order_id = ?");
        $stmt->execute([$status, $orderId]);
    } catch (Exception $e) {
        throw new Exception("Fout bij het updaten van de bestelstatus: " . $e->getMessage());
    }
}

function updateToegewezenMedewerker($orderId, $username) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("UPDATE Pizza_Order SET personnel_username = ? WHERE order_id = ?");
        $stmt->execute([$username, $orderId]);
    } catch (Exception $e) {
        throw new Exception("Fout bij het bijwerken van toegewezen medewerker: " . $e->getMessage());
    }
}

function haalAllePersoneelsleden() {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("SELECT username FROM [User] WHERE role = 'Personnel'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Fout bij ophalen van personeelsleden: " . $e->getMessage());
    }
}
?>