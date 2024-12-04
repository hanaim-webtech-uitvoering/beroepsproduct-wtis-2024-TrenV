<?php
session_start();
require_once '../db_connectie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Voeg een product toe aan het winkelmandje
    if ($action === 'add_to_cart') {
        $productName = $_POST['product_name'] ?? '';
        $quantity = intval($_POST['quantity'] ?? 1);

        if (!empty($productName)) {
            // Controleer of er al een bestelling bestaat in de sessie
            if (!isset($_SESSION['bestelling'])) {
                $_SESSION['bestelling'] = [];
            }

            $gevonden = false;

            // Kijk of het product al in het winkelmandje zit
            foreach ($_SESSION['bestelling'] as &$product) {
                if ($product['name'] === $productName) {
                    $product['quantity'] += $quantity;
                    $gevonden = true;
                    break;
                }
            }

            // Voeg een nieuw product toe als het nog niet bestaat
            if (!$gevonden) {
                $_SESSION['bestelling'][] = [
                    'name' => $productName,
                    'quantity' => $quantity,
                ];
            }

            header('Location: ../paginas/menu.php'); // Terug naar menu
            exit;
        } else {
            die("Fout: Productnaam ontbreekt.");
        }
    }

    // Verwijder één exemplaar van een product uit het winkelmandje
    if ($action === 'remove_one') {
        $productName = $_POST['product_name'] ?? '';

        if (!empty($productName) && isset($_SESSION['bestelling'])) {
            foreach ($_SESSION['bestelling'] as $key => &$product) {
                if ($product['name'] === $productName) {
                    $product['quantity'] -= 1;
                    if ($product['quantity'] <= 0) {
                        unset($_SESSION['bestelling'][$key]);
                    }
                    break;
                }
            }

            header('Location: ../paginas/winkelmandje.php');
            exit;
        } else {
            die("Fout: Productnaam ontbreekt of winkelmandje is leeg.");
        }
    }

    // Verwijder alle exemplaren van een product uit het winkelmandje
    if ($action === 'remove_all') {
        $productName = $_POST['product_name'] ?? '';

        if (!empty($productName) && isset($_SESSION['bestelling'])) {
            foreach ($_SESSION['bestelling'] as $key => $product) {
                if ($product['name'] === $productName) {
                    unset($_SESSION['bestelling'][$key]);
                    break;
                }
            }

            header('Location: ../paginas/winkelmandje.php');
            exit;
        } else {
            die("Fout: Productnaam ontbreekt of winkelmandje is leeg.");
        }
    }

    // Afronden van de bestelling
    if ($action === 'checkout') {
        if (isset($_SESSION['bestelling']) && !empty($_SESSION['bestelling'])) {
            // Hier zou de code komen voor het verwerken van de bestelling
            header('Location: ../paginas/bestellen.php');
            exit;
        } else {
            die("Uw winkelmandje is leeg. Voeg eerst producten toe.");
        }
    }

    die("Ongeldige actie.");
} else {
    die("Ongeldig verzoek.");
}
?>