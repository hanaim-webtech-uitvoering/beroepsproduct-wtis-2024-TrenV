<?php
session_start();
require '../db_connectie.php';

// Controleer of de gebruiker bevoegd is om de status te wijzigen
function controleerToegang() {
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Personnel') {
        die("U moet ingelogd zijn als medewerker om deze actie uit te voeren.");
    }
}

// Verwerk een statuswijziging
function updateStatus($order_id, $status) {
    try {
        $db = maakVerbinding();

        // Valideer invoer
        if (!in_array($status, ['0', '1', '2'])) {
            die("Ongeldige statuswaarde.");
        }

        // Update de status in de database
        $stmt = $db->prepare("UPDATE Pizza_Order SET status = ? WHERE order_id = ?");
        $stmt->execute([$status, $order_id]);

        echo "Status van bestelling $order_id succesvol bijgewerkt.";
    } catch (Exception $e) {
        die("Fout bij het updaten van de status: " . $e->getMessage());
    }
}

// Verwerk het POST-verzoek
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    controleerToegang();

    $order_id = $_POST['order_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$order_id || $status === null) {
        die("Ongeldige invoer. Zorg ervoor dat zowel order_id als status is ingevuld.");
    }

    updateStatus($order_id, $status);

    // Terug naar het overzicht
    header("Location: ../paginas/bestelOverzicht.php");
    exit;
}
?>