<?php
session_start();
require_once '../db_connectie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add_to_cart' && isset($_POST['product_name'])) {
        $productName = $_POST['product_name'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        if (!isset($_SESSION['bestelling'])) {
            $_SESSION['bestelling'] = [];
        }

        $gevonden = false;

        foreach ($_SESSION['bestelling'] as &$product) {
            if ($product['name'] === $productName) {
                $product['quantity'] += $quantity;
                $gevonden = true;
                break;
            }
        }

        if (!$gevonden) {
            $_SESSION['bestelling'][] = [
                'name' => $productName,
                'quantity' => $quantity,
            ];
        }

        header('Location: ../paginas/menu.php');
        exit;
    }

    if ($action === 'checkout' && isset($_SESSION['bestelling']) && !empty($_SESSION['bestelling'])) {
        try {
            $db = maakVerbinding();

            $client_username = $_SESSION['username'] ?? null; // Gebruiker uit de sessie
            $address = $_POST['adres'] ?? null; // Adres uit formulier
            $name = $_POST['naam'] ?? null;

            if (!$client_username || !$address || !$name) {
                die("Vul alstublieft alle verplichte velden in.");
            }

            $datetime = date('Y-m-d H:i:s');
            $status = 0;
            $personnel_username = 'admin'; // Statische placeholder voor medewerker

            $db->beginTransaction();

            $stmt = $db->prepare("
                INSERT INTO Pizza_Order (client_username, personnel_username, datetime, status, address)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$client_username, $personnel_username, $datetime, $status, $address]);

            $order_id = $db->lastInsertId();

            $stmt = $db->prepare("
                INSERT INTO Pizza_Order_Product (order_id, product_name, quantity)
                VALUES (?, ?, ?)
            ");

            foreach ($_SESSION['bestelling'] as $product) {
                $stmt->execute([$order_id, $product['name'], $product['quantity']]);
            }

            $db->commit();

            unset($_SESSION['bestelling']);

            header("Location: ../paginas/bevestiging.php?order_id=$order_id");
            exit;
        } catch (Exception $e) {
            $db->rollBack();
            echo "Er is een fout opgetreden: " . $e->getMessage();
        }
    }

    die("Ongeldige actie of onvolledige gegevens.");
}
?>