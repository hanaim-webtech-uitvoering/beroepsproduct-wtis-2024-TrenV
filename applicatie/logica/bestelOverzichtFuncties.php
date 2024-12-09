<?php
session_start();
require_once '../data/bestellingenData.php';

/**
 * Controleer of de gebruiker is ingelogd als medewerker.
 */
function controleerToegang() {
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Personnel') {
        die("U moet ingelogd zijn als medewerker om deze pagina te bekijken.");
    }
}

/**
 * Haal het volledige overzicht van bestellingen op.
 */
function haalOverzichtBestellingen() {
    return haalAlleBestellingenOp();
}

/**
 * Werk de status van een bestelling bij.
 */
function wijzigBestellingStatus($orderId, $status) {
    updateBestellingStatus($orderId, $status);
}