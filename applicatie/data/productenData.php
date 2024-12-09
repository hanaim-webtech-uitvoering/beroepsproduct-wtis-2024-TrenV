<?php
require_once 'db_connectie.php';

/**
 * Haal alle producten op inclusief hun type.
 */
function haalAlleProductenOp() {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("
            SELECT 
                p.name, 
                p.price, 
                pt.name AS type_name
            FROM 
                Product p
            JOIN 
                ProductType pt ON p.type_id = pt.name
            ORDER BY 
                type_name DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Fout bij het ophalen van producten: " . htmlspecialchars($e->getMessage()));
    }
}

/**
 * Voeg een nieuw product toe aan het menu.
 */
function voegProductToe($naam, $prijs, $typeId) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("INSERT INTO Product (name, price, type_id) VALUES (?, ?, ?)");
        $stmt->execute([$naam, $prijs, $typeId]);
    } catch (Exception $e) {
        die("Fout bij het toevoegen van een product: " . htmlspecialchars($e->getMessage()));
    }
}

/**
 * Update een bestaand product in het menu.
 */
function updateProduct($naam, $prijs, $typeId) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("UPDATE Product SET price = ?, type_id = ? WHERE name = ?");
        $stmt->execute([$prijs, $typeId, $naam]);
    } catch (Exception $e) {
        die("Fout bij het updaten van een product: " . htmlspecialchars($e->getMessage()));
    }
}

/**
 * Verwijder een product uit het menu.
 */
function verwijderProduct($naam) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("DELETE FROM Product WHERE name = ?");
        $stmt->execute([$naam]);
    } catch (Exception $e) {
        die("Fout bij het verwijderen van een product: " . htmlspecialchars($e->getMessage()));
    }
}