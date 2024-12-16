<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../data/bestellingenData.php';

/**
 * Voeg een product toe aan het winkelmandje.
 */
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

/**
 * Haal het winkelmandje op.
 */
function haalWinkelmandjeOp() {
    return $_SESSION['bestelling'] ?? [];
}

/**
 * Verwijder één product uit het winkelmandje.
 */
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

/**
 * Verwijder alle producten uit het winkelmandje.
 */
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

/**
 * Verwerk het afronden van een bestelling.
 */
function afrondenBestelling($klantNaam, $adres) {
    if (!isset($_SESSION['bestelling']) || empty($_SESSION['bestelling'])) {
        die("Uw winkelmandje is leeg. Voeg eerst producten toe.");
    }

    voegBestellingToe($klantNaam, $adres, $_SESSION['bestelling']); // Functie uit bestellingenData.php
    $_SESSION['bestelling'] = []; // Leeg winkelmandje na afronding
}
?>