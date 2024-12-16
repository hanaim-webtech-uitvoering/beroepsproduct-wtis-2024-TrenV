<?php
require_once 'db_connectie.php';

/**
 * Voeg een nieuwe bestelling toe aan de database.
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
?>