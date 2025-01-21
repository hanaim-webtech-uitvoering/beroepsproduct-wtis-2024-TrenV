<?php
require_once '../data/bestellingenData.php';

function controleerToegang() {
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Personnel') {
        die("U moet ingelogd zijn als medewerker om deze pagina te bekijken.");
    }
}


function haalOverzichtBestellingen() {
    return haalAlleBestellingenOp();
}



function wijzigBestellingStatus($orderId, $status) {
    updateBestellingStatus($orderId, $status);
}