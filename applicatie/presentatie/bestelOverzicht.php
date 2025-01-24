<?php
require_once '../logica/paginaFuncties.php';
require_once '../logica/bestelOverzichtFuncties.php';

controleerToegang();

$bestellingen = haalOverzichtBestellingen();

maakHead();
maakHeader("Besteloverzicht Personeel");
?>

    <body>
    <table border="1">
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
                <?php
                $formattedDateTime = (new DateTime($bestelling['datetime']))->format('Y-m-d H:i');
                ?>
                <tr>
                    <td><?= htmlspecialchars($bestelling['order_id']) ?></td>
                    <td><?= htmlspecialchars($bestelling['client_name']) ?></td>
                    <td><?= htmlspecialchars($formattedDateTime) ?></td>
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

    <a href="bestelOverzicht.php">Terug naar het overzicht</a>
    </body>

<?php maakFooter(); ?>