<?php
require '../functies/paginaFuncties.php';
require '../functies/bestelOverzichtFuncties.php';

// Controleer of de gebruiker toegang heeft
controleerToegang();

// Haal alle bestellingen op
$bestellingen = haalAlleBestellingenOp();

maakHead();
maakHeader("Besteloverzicht Personeel");
?>

    <body>
    <h1>Besteloverzicht</h1>

    <!-- Tabel met bestellingen -->
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
                <tr>
                    <td><?= htmlspecialchars($bestelling['order_id']) ?></td>
                    <td><?= htmlspecialchars($bestelling['client_name']) ?></td>
                    <td><?= htmlspecialchars($bestelling['datetime']) ?></td>
                    <td><?= htmlspecialchars($bestelling['products']) ?></td>
                    <td><?= htmlspecialchars($bestelling['address']) ?></td>
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
                    <td>
                        <!-- Link naar detailpagina -->
                        <a href="../paginas/bestellingdetails.php?order_id=<?= htmlspecialchars($bestelling['order_id']) ?>">Bekijk details</a>

                        <!-- Formulier voor statuswijziging -->
                        <form method="post" action="../functies/statusFuncties.php" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($bestelling['order_id']) ?>">
                            <select name="status" required>
                                <option value="0" <?= $bestelling['status'] == 0 ? 'selected' : '' ?>>In behandeling</option>
                                <option value="1" <?= $bestelling['status'] == 1 ? 'selected' : '' ?>>Klaar voor bezorging</option>
                                <option value="2" <?= $bestelling['status'] == 2 ? 'selected' : '' ?>>Bezorgd</option>
                            </select>
                            <button type="submit">Update Status</button>
                        </form>
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