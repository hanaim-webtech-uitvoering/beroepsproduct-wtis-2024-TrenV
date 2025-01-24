<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../data/bestellingenData.php';

function voegProductToeAanWinkelmandje($productName, $quantity) {
    if (!isset($_SESSION['bestelling'])) {
        $_SESSION['bestelling'] = [];
    }

    foreach ($_SESSION['bestelling'] as &$product) {
        if ($product['name'] === $productName) {
            $product['quantity'] += $quantity;
            return;
        }
    }

    $_SESSION['bestelling'][] = [
        'name' => $productName,
        'quantity' => $quantity,
    ];
}

function haalWinkelmandjeOp() {
    return $_SESSION['bestelling'] ?? [];
}

function verwijderProductUitWinkelmandje($productName) {
    if (isset($_SESSION['bestelling'])) {
        foreach ($_SESSION['bestelling'] as $key => &$product) {
            if ($product['name'] === $productName) {
                $product['quantity'] -= 1;
                if ($product['quantity'] <= 0) {
                    unset($_SESSION['bestelling'][$key]);
                }
                break;
            }
        }
    }
}

function verwijderAlleProductenUitWinkelmandje($productName) {
    if (isset($_SESSION['bestelling'])) {
        foreach ($_SESSION['bestelling'] as $key => $product) {
            if ($product['name'] === $productName) {
                unset($_SESSION['bestelling'][$key]);
                break;
            }
        }
    }
}

function afrondenBestelling($klantNaam, $adres) {
    if (!isset($_SESSION['bestelling']) || empty($_SESSION['bestelling'])) {
        die("Uw winkelmandje is leeg. Voeg eerst producten toe.");
    }

    try {
        $orderId = voegBestellingToe($klantNaam, $adres, $_SESSION['bestelling']);

        $_SESSION['bestelling_afgerond_details'] = [
            'naam' => $klantNaam,
            'adres' => $adres,
            'order_id' => $orderId,
            'producten' => $_SESSION['bestelling']
        ];

        $_SESSION['bestelling'] = [];
    } catch (Exception $e) {
        die("Fout bij afronden bestelling: " . htmlspecialchars($e->getMessage()));
    }
}
?>