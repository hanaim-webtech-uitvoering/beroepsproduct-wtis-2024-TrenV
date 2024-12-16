<?php
require_once 'db_connectie.php';

/**
 * Haal een gebruiker op basis van gebruikersnaam en rol.
 *
 * @param string $username
 * @param string $role
 * @return array|null
 * @throws Exception Als er een fout optreedt bij de databasequery.
 */
function haalGebruikerOp($username, $role) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("
            SELECT username, password, role 
            FROM [User] 
            WHERE username = ? AND role = ?
        ");
        $stmt->execute([$username, $role]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Fout bij ophalen van gebruiker: " . $e->getMessage());
    }
}

/**
 * Update een wachtwoord van een gebruiker.
 *
 * @param string $username
 * @param string $hashedPassword
 * @throws Exception Bij een fout tijdens de update.
 */
function updateWachtwoord($username, $hashedPassword) {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("UPDATE [User] SET password = ? WHERE username = ?");
        $stmt->execute([$hashedPassword, $username]);
    } catch (Exception $e) {
        throw new Exception("Fout bij updaten van wachtwoord: " . $e->getMessage());
    }
}

/**
 * Update alle wachtwoorden in de database die nog niet gehashed zijn.
 *
 * @throws Exception Bij een fout tijdens de update.
 */
function updateAlleWachtwoorden() {
    try {
        $db = maakVerbinding();
        $stmt = $db->prepare("SELECT username, password FROM [User]");
        $stmt->execute();
        $gebruikers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($gebruikers as $gebruiker) {
            if (password_get_info($gebruiker['password'])['algo'] === 0) {
                $hashedPassword = password_hash($gebruiker['password'], PASSWORD_DEFAULT);
                updateWachtwoord($gebruiker['username'], $hashedPassword);
            }
        }
    } catch (Exception $e) {
        throw new Exception("Fout bij updaten van wachtwoorden: " . $e->getMessage());
    }
}