<?php
require_once '../data/gebruikersData.php';

/**
 * Haal een gebruiker op en valideer de rol.
 *
 * @param string $username
 * @param string $role
 * @return array
 * @throws Exception Als de gebruiker niet bestaat of de rol niet klopt.
 */
function valideerGebruiker($username, $role) {
    $gebruiker = haalGebruikerOp($username, $role);

    if (!$gebruiker) {
        throw new Exception("Gebruiker bestaat niet of rol komt niet overeen.");
    }

    return $gebruiker;
}

/**
 * Update wachtwoorden voor alle gebruikers.
 *
 * @throws Exception Bij een fout tijdens het updaten.
 */
function updateWachtwoorden() {
    try {
        updateAlleWachtwoorden(); // Functie uit gebruikersData.php
    } catch (Exception $e) {
        throw new Exception("Fout bij het updaten van wachtwoorden: " . $e->getMessage());
    }
}