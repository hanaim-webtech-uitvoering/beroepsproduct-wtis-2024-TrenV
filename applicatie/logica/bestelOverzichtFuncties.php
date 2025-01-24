<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../data/bestellingenData.php';

function controleerToegang() {
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Personnel') {
        echo "<pre>DEBUG: Sessie-inhoud\n";
        print_r($_SESSION);
        echo "</pre>";

        die("
            U moet ingelogd zijn als medewerker om deze pagina te bekijken.
            <br><br>
            <a href='../presentatie/medewerkerLogin.php'>Klik hier om in te loggen</a>.
        ");
    }
}

function haalOverzichtBestellingen() {
    return haalAlleBestellingenOp();
}