<?php
require_once '../logica/paginaFuncties.php';
require_once '../logica/bestelOverzichtFuncties.php';

// Controleer toegang
controleerToegang();

// Haal alle bestellingen op
$bestellingen = haalOverzichtBestellingen();

maakHead();
maakHeader("Besteloverzicht Personeel");
?>

<body>
<!-- Tabel met bestellingen -->
<table>
    <thead>
    <tr>
        <th>Ordernummer</th>
        <th>Klant</th>
        <th>Datum en Tijd</th>
        <th>Inhoud</th>
        <th>Adres</th>
        <th>Status</th>
        <th>Acties</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($bestellingen)): ?>
        <?php foreach ($bestellingen as $bestelling): ?>
            <tr>
                <td><?= htmlspecialchars($bestelling['order_id']) ?></td>
                <td><?= htmlspecialchars($bestelling['client_name']) ?></td>
                <td><?= htmlspecialchars($bestelling['datetime']) ?></td>
                <td><?= htmlspecialchars($bestelling['producten']) ?></td>
                <td><?= htmlspecialchars($bestelling['address']) ?></td>
                <td><?= htmlspecialchars($bestelling['status']) ?></td>
                <td>
                    <a href="bestellingDetails.php?order_id=<?= htmlspecialchars($bestelling['order_id']) ?>">Bekijk details</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">Geen bestellingen gevonden.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</body>

<?php maakFooter(); ?>