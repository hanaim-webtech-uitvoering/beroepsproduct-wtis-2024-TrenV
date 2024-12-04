<?php
require_once '../db_connectie.php';

// Controleer of de gebruiker is ingelogd als personeel
function controleerToegang() {
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Personnel') {
        die("U moet ingelogd zijn als medewerker om deze pagina te bekijken.");
    }
}

// Haal alle bestellingen op uit de database
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
        die("Fout bij het ophalen van bestellingen: " . $e->getMessage());
    }
}
?>