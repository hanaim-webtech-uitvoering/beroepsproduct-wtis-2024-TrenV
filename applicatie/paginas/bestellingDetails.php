<?php
session_start();
require '../db_connectie.php';
require '../functies/paginaFuncties.php';

// Controleer of de gebruiker ingelogd is
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'Personnel' && $_SESSION['role'] !== 'bezorger')) {
    die("U moet ingelogd zijn als medewerker of bezorger om deze pagina te bekijken.");
}

// Haal het order_id uit de querystring
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    die("Geen bestelling geselecteerd. Ga terug naar het overzicht.");
}

try {
    $db = maakVerbinding();

    // Haal bestellinggegevens op (zonder samenvatting)
    $stmt = $db->prepare("
        SELECT 
            po.order_id,
            po.client_name,
            po.address,
            po.datetime,
            po.status,
            pop.product_name,
            pop.quantity,
            STRING_AGG(i.name, ', ') AS ingredients
        FROM 
            Pizza_Order po
        JOIN 
            Pizza_Order_Product pop ON po.order_id = pop.order_id
        LEFT JOIN 
            Product_Ingredient pi ON pop.product_name = pi.product_name
        LEFT JOIN 
            Ingredient i ON pi.ingredient_name = i.name
        WHERE 
            po.order_id = ?
        GROUP BY 
            po.order_id, po.client_name, po.address, po.datetime, po.status, pop.product_name, pop.quantity
        ORDER BY 
            pop.product_name
    ");
    $stmt->execute([$order_id]);
    $bestellingDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($bestellingDetails)) {
        die("Bestelling niet gevonden.");
    }

    // Haal algemene gegevens van de bestelling (eerste rij)
    $algemeneDetails = $bestellingDetails[0];

} catch (Exception $e) {
    die("Fout bij het ophalen van de bestelling: " . $e->getMessage());
}

maakHead();
maakHeader("Bestellingdetails");
?>

    <body>
    <h1>Bestellingdetails</h1>

    <!-- Bestellinggegevens -->
    <h2>Bestelling #<?= htmlspecialchars($algemeneDetails['order_id']) ?></h2>
    <table border="1">
        <tr>
            <th>Klantnaam:</th>
            <td><?= htmlspecialchars($algemeneDetails['client_name']) ?></td>
        </tr>
        <tr>
            <th>Adres:</th>
            <td><?= htmlspecialchars($algemeneDetails['address']) ?></td>
        </tr>
        <tr>
            <th>Datum en Tijd:</th>
            <td><?= htmlspecialchars($algemeneDetails['datetime']) ?></td>
        </tr>
        <tr>
            <th>Inhoud:</th>
            <td>
                <ul>
                    <?php foreach ($bestellingDetails as $item): ?>
                        <li>
                            <?= htmlspecialchars($item['product_name']) ?> (<?= htmlspecialchars($item['quantity']) ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
        <tr>
            <th>Ingrediënten:</th>
            <td>
                <ul>
                    <?php foreach ($bestellingDetails as $item): ?>
                        <?php if (!empty($item['ingredients'])): ?>
                            <li>
                                <strong><?= htmlspecialchars($item['product_name']) ?>:</strong>
                                <ul>
                                    <?php foreach (explode(', ', $item['ingredients']) as $ingredient): ?>
                                        <li><?= htmlspecialchars($ingredient) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
        <tr>
            <th>Status:</th>
            <td>
                <?php
                switch ($algemeneDetails['status']) {
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
    </table>

    <br>
    <a href="../paginas/besteloverzicht.php">Terug naar het overzicht</a>
    </body>

<?php maakFooter(); ?>