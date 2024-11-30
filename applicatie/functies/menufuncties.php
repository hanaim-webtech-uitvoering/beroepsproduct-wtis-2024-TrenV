<?php 
require_once '../db_connectie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'] ?? '';

    if ($productName) {
        try {
            $db = maakVerbinding();

            $orderId = null;

            $checkOrderQuery = $db->prepare("SELECT MAX(order_id) as last_order_id FROM Pizza_Order");
            $checkOrderQuery->execute();
            $result = $checkOrderQuery->fetch(PDO::FETCH_ASSOC);
            $orderId = $result['last_order_id'] ? $result['last_order_id'] + 1 : 1;

            $insertOrderQuery = $db->prepare("
                INSERT INTO Pizza_Order (order_id, client_username, datetime, status, address)
                VALUES (:order_id, :client_username, NOW(), 0, :address)
            ");
            $insertOrderQuery->execute([
                ':order_id' => $orderId,
                ':client_username' => 'testuser', 
                ':address' => 'Straatnaam 123' 
            ]);

            $insertProductQuery = $db->prepare("
                INSERT INTO Pizza_Order_Product (order_id, product_name, quantity)
                VALUES (:order_id, :product_name, 1)
            ");
            $insertProductQuery->execute([
                ':order_id' => $orderId,
                ':product_name' => $productName,
            ]);

            echo "Product '$productName' is toegevoegd aan de bestelling!";
        } catch (PDOException $e) {
            echo "Fout bij het toevoegen aan de bestelling: " . $e->getMessage();
        }
    } else {
        echo "Geen product geselecteerd.";
    }
}
?>