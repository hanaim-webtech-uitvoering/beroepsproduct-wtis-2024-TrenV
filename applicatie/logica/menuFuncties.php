<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../data/productenData.php';
require_once '../logica/bestelFuncties.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';
    $redirectUrl = '../presentatie/menu.php';

    try {
        switch ($action) {
            case 'add_to_cart':
                if (!empty($_POST['product_name']) && isset($_POST['quantity'])) {
                    voegProductToeAanWinkelmandje($_POST['product_name'], intval($_POST['quantity']));
                } else {
                    throw new Exception("Onjuiste invoer bij het toevoegen aan winkelmandje.");
                }
                break;

            case 'remove_one':
                if (!empty($_POST['product_name'])) {
                    verwijderProductUitWinkelmandje($_POST['product_name']);
                } else {
                    throw new Exception("Productnaam ontbreekt bij verwijderen.");
                }
                break;

            case 'remove_all':
                if (!empty($_POST['product_name'])) {
                    verwijderAlleProductenUitWinkelmandje($_POST['product_name']);
                } else {
                    throw new Exception("Productnaam ontbreekt bij volledig verwijderen.");
                }
                break;

            case 'checkout':
                if (!empty($_POST['naam']) && !empty($_POST['adres'])) {
                    afrondenBestelling($_POST['naam'], $_POST['adres']);
                    $_SESSION['bestelling_afgerond'] = true;
                    $redirectUrl = '../presentatie/bestellen.php';
                } else {
                    throw new Exception("Naam of adres ontbreekt bij afrekenen.");
                }
                break;

            default:
                throw new Exception("Onbekende actie: $action");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }


    header("Location: $redirectUrl");
    exit;
}