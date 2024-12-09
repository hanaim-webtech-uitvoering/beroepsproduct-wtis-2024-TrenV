<?php
require_once '../helpers/paginaFuncties.php';
require_once '../logica/bestelOverzichtFuncties.php';

// Controleer of de gebruiker toegang heeft
controleerToegang();

// Haal bestellingdetails op
$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    die("Geen bestelling geselecteerd. Ga terug naar het overzicht.");
}
$bestellingDetails = haalBestellingDetails($order_id);

maakHead();
maakHeader("Bestellingdetails");
?>

<body>
<h1>Bestellingdetails</h1>

<!-- Bestellinggegevens -->
<h2>Bestelling #<?= htmlspecialchars($bestellingDetails['order_id']) ?></h2>
<table border="1">
    <tr>
        <th>Klantnaam:</th>
        <td><?= htmlspecialchars($bestellingDetails['client_name']) ?></td>
    </tr>
    <tr>
        <th>Adres:</th>
        <td><?= htmlspecialchars($bestellingDetails['address']) ?></td>
    </tr>
    <tr>
        <th>Datum en Tijd:</th>
        <td><?= htmlspecialchars($bestellingDetails['datetime']) ?></td>
    </tr>
    <tr>
        <th>Inhoud:</th>
        <td>
            <ul>
                <?php foreach ($bestellingDetails['producten'] as $product): ?>
                    <li>
                        <?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['quantity']) ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <th>Status:</th>
        <td><?= htmlspecialchars($bestellingDetails['status']) ?></td>
    </tr>
</table>

<a href="../presentatie/bestelOverzicht.php">Terug naar het overzicht</a>
</body>

<?php maakFooter(); ?>