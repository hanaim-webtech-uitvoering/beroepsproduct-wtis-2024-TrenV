<?php
require_once '../data/gebruikersData.php';


function valideerGebruiker($username, $role) {
    $gebruiker = haalGebruikerOp($username, $role);

    if (!$gebruiker) {
        throw new Exception("Gebruiker bestaat niet of rol komt niet overeen.");
    }

    return $gebruiker;
}


function updateWachtwoorden() {
    try {
        updateAlleWachtwoorden();
    } catch (Exception $e) {
        throw new Exception("Fout bij het updaten van wachtwoorden: " . $e->getMessage());
    }
}