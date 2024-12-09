<?php
require_once 'db_connectie.php';

/**
 * Haal alle bestellingen op, inclusief producten en hun hoeveelheden.
 */
function haalAlleBestellingenOp() {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("
            SELECT 
                po.order_id,
                po.client_name,
                po.datetime,
                po.status,
                po.address,
                STRING_AGG(CONCAT(pop.product_name, ' (', pop.quantity, ')'), ', ') AS products
            FROM 
                Pizza_Order po
            JOIN 
                Pizza_Order_Product pop ON po.order_id = pop.order_id
            GROUP BY 
                po.order_id, po.client_name, po.datetime, po.status, po.address
            ORDER BY 
                po.datetime DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Fout bij het ophalen van bestellingen: " . htmlspecialchars($e->getMessage()));
    }
}

/**
 * Haal een specifieke bestelling op inclusief details.
 */
function haalBestellingDetailsOp($orderId) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("
            SELECT 
                po.order_id,
                po.client_name,
                po.datetime,
                po.status,
                po.address,
                pop.product_name,
                pop.quantity
            FROM 
                Pizza_Order po
            JOIN 
                Pizza_Order_Product pop ON po.order_id = pop.order_id
            WHERE 
                po.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Fout bij het ophalen van bestellingdetails: " . htmlspecialchars($e->getMessage()));
    }
}

/**
 * Update de status van een bestelling.
 */
function updateBestellingStatus($orderId, $status) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("UPDATE Pizza_Order SET status = ? WHERE order_id = ?");
        $stmt->execute([$status, $orderId]);
    } catch (Exception $e) {
        die("Fout bij het updaten van de bestelstatus: " . htmlspecialchars($e->getMessage()));
    }
}

/**
 * Voeg een nieuwe bestelling toe.
 */
function voegBestellingToe($klantNaam, $adres, $producten) {
    try {
        $db = maakVerbinding();
        $db->beginTransaction();

        // Voeg bestelling toe
        $stmt = $db->prepare("
            INSERT INTO Pizza_Order (client_name, address, datetime, status) 
            VALUES (?, ?, GETDATE(), 0)
        ");
        $stmt->execute([$klantNaam, $adres]);
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
        die("Fout bij het toevoegen van de bestelling: " . htmlspecialchars($e->getMessage()));
    }
}