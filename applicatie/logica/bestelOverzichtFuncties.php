<?php
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
 *
 * @return array Alle bestellingen inclusief details.
 */
function haalOverzichtBestellingen() {
    return haalAlleBestellingenOp(); // Haalt bestellingen op via de datalaag.
}

/**
 * Werk de status van een bestelling bij.
 *
 * @param int $orderId Het ID van de bestelling die moet worden bijgewerkt.
 * @param int $status De nieuwe status van de bestelling.
 */
function wijzigBestellingStatus($orderId, $status) {
    updateBestellingStatus($orderId, $status); // Update status via de datalaag.
}