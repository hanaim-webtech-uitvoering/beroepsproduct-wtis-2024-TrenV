<?php
require_once 'db_connectie.php';

/**
 * Haal alle producten op inclusief hun type.
 *
 * @return array De lijst met producten.
 * @throws Exception Bij een fout tijdens de databasequery.
 */
function haalAlleProductenOp() {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("
            SELECT p.name, p.price, pt.name AS type_name
            FROM Product p
            JOIN ProductType pt ON p.type_id = pt.name
            ORDER BY type_name DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Databasefout bij ophalen van producten: " . $e->getMessage());
    }
}


function voegProductToe($naam, $prijs, $typeId) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("INSERT INTO Product (name, price, type_id) VALUES (?, ?, ?)");
        $stmt->execute([$naam, $prijs, $typeId]);
    } catch (PDOException $e) {
        throw new Exception("Databasefout bij toevoegen van product: " . $e->getMessage());
    }
}


function verwijderProduct($naam) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("DELETE FROM Product WHERE name = ?");
        $stmt->execute([$naam]);
    } catch (PDOException $e) {
        throw new Exception("Databasefout bij verwijderen van product: " . $e->getMessage());
    }
}