<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../data/productenData.php';
require_once '../logica/bestelFuncties.php';

function haalMenuProductenOp() {
    return haalAlleProductenOp();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $redirectUrl = '../presentatie/menu.php'; // Standaard terug naar menu na actie

    switch ($action) {
        case 'add_to_cart':
            voegProductToeAanWinkelmandje($_POST['product_name'], intval($_POST['quantity']));
            // Blijf op menu.php
            break;

        case 'remove_one':
            verwijderProductUitWinkelmandje($_POST['product_name']);
            $redirectUrl = '../presentatie/winkelmandje.php'; // Terug naar winkelmandje
            break;

        case 'remove_all':
            verwijderAlleProductenUitWinkelmandje($_POST['product_name']);
            $redirectUrl = '../presentatie/winkelmandje.php'; // Terug naar winkelmandje
            break;

        case 'checkout':
            afrondenBestelling($_POST['naam'], $_POST['adres']);
            $redirectUrl = '../presentatie/bevestiging.php'; // Stuur gebruiker naar een bevestigingspagina
            break;

        default:
            // Onbekende actie, terug naar menu
            $redirectUrl = '../presentatie/menu.php';
            break;
    }

    // Redirect naar de juiste pagina na de actie
    header("Location: $redirectUrl");
    exit;
}