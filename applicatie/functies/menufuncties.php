<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'] ?? '';
    $quantity = $_POST['quantity'] ?? 1;

    if ($productName) {
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
            $_SESSION['bestelling'][] = ['name' => $productName, 'quantity' => $quantity];
        }
    }
    header('Location: ../pages/winkelmandje.php'); // Verwijs naar winkelmandje
    exit;
}
?>