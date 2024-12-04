<?php
session_start();
require '../db_connectie.php';
require '../functies/paginaFuncties.php';

// Controleer of de klant is ingelogd
if (!isset($_SESSION['username'])) {
    die("U moet ingelogd zijn om uw profiel te bekijken.");
}

$username = $_SESSION['username'];

try {
    $db = maakVerbinding();

    // Haal klantgegevens op
    $stmt = $db->prepare("SELECT first_name, last_name, address FROM [User] WHERE username = ? AND role = 'klant'");
    $stmt->execute([$username]);
    $klantGegevens = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$klantGegevens) {
        die("Klantgegevens niet gevonden. Neem contact op met de klantenservice.");
    }

    // Haal bestellingen op
    $stmt = $db->prepare("
        SELECT 
            po.order_id,
            po.datetime,
            po.status,
            STRING_AGG(CONCAT(pop.product_name, ' (', pop.quantity, ')'), ', ') AS products
        FROM 
            Pizza_Order po
        JOIN 
            Pizza_Order_Product pop ON po.order_id = pop.order_id
        WHERE 
            po.client_username = ?
        GROUP BY 
            po.order_id, po.datetime, po.status
        ORDER BY 
            po.datetime DESC
    ");
    $stmt->execute([$username]);
    $bestellingen = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Fout bij het ophalen van gegevens: " . $e->getMessage());
}

maakHead();
maakHeader("Profiel");
?>

    <body>
    <h1>Welkom op uw profielpagina</h1>

    <!-- Klantgegevens -->
    <h2>Uw gegevens</h2>
    <table border="1">
        <tr>
            <th>Naam:</th>
            <td><?= htmlspecialchars($klantGegevens['first_name'] . ' ' . $klantGegevens['last_name']) ?></td>
        </tr>
        <tr>
            <th>Adres:</th>
            <td><?= htmlspecialchars($klantGegevens['address']) ?></td>
        </tr>
        <tr>
            <th>Gebruikersnaam:</th>
            <td><?= htmlspecialchars($username) ?></td>
        </tr>
    </table>

    <!-- Bestellingen -->
    <h2>Uw bestellingen</h2>
    <?php if (!empty($bestellingen)): ?>
        <table border="1">
            <thead>
            <tr>
                <th>Ordernummer</th>
                <th>Datum en Tijd</th>
                <th>Inhoud</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bestellingen as $bestelling): ?>
                <tr>
                    <td><?= htmlspecialchars($bestelling['order_id']) ?></td>
                    <td><?= htmlspecialchars($bestelling['datetime']) ?></td>
                    <td><?= htmlspecialchars($bestelling['products']) ?></td>
                    <td>
                        <?php
                        switch ($bestelling['status']) {
                            case 0:
                                echo "In behandeling";
                                break;
                            case 1:
                                echo "Klaar voor bezorging";
                                break;
                            case 2:
                                echo "Bezorgd";
                                break;
                            default:
                                echo "Onbekend";
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>U heeft nog geen bestellingen geplaatst.</p>
    <?php endif; ?>
    </body>

<?php maakFooter(); ?>