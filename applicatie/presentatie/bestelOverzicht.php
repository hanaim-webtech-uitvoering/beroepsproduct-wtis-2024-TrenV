<?php
require_once '../logica/paginaFuncties.php';
require_once '../logica/bestelOverzichtFuncties.php';

controleerToegang();

$sortColumn = $_GET['sort'] ?? 'datetime';
$sortOrder = $_GET['order'] ?? 'desc'; 

$nextSortOrder = $sortOrder === 'asc' ? 'desc' : 'asc';

$bestellingen = haalOverzichtBestellingenGesorteerd($sortColumn, $sortOrder);

maakHead();
maakHeader("Besteloverzicht Personeel");
?>

    <body>
    <h1>Overzicht van Bestellingen</h1>
    <table border="1">
        <thead>
        <tr>
            <th><a href="?sort=order_id&order=<?= $nextSortOrder ?>">Ordernummer</a></th>
            <th>Klant</th>
            <th><a href="?sort=datetime&order=<?= $nextSortOrder ?>">Datum en Tijd</a></th>
            <th>Inhoud</th>
            <th>Adres</th>
            <th><a href="?sort=status&order=<?= $nextSortOrder ?>">Status</a></th>
            <th><a href="?sort=personnel_username&order=<?= $nextSortOrder ?>">Medewerker</a></th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($bestellingen)): ?>
            <?php foreach ($bestellingen as $bestelling): ?>
                <?php
                $formattedDateTime = (new DateTime($bestelling['datetime']))->format('Y-m-d H:i');
                $statusLabels = ['Nieuw', 'In de oven', 'Onderweg', 'Afgeleverd'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($bestelling['order_id']) ?></td>
                    <td><?= htmlspecialchars($bestelling['client_name']) ?></td>
                    <td><?= htmlspecialchars($formattedDateTime) ?></td>
                    <td><?= htmlspecialchars($bestelling['producten']) ?></td>
                    <td><?= htmlspecialchars($bestelling['address']) ?></td>
                    <td><?= htmlspecialchars($statusLabels[$bestelling['status']] ?? 'Onbekend') ?></td>
                    <td><?= htmlspecialchars($bestelling['personnel_username'] ?? 'Niet toegewezen') ?></td>
                    <td>
                        <a href="bestellingDetails.php?order_id=<?= htmlspecialchars($bestelling['order_id']) ?>">Bekijk details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Geen bestellingen gevonden.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="../presentatie/medewerkerLogin.php">Uitloggen</a>
    </body>

<?php maakFooter(); ?>