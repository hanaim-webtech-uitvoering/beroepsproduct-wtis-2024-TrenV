<?php
require_once '../logica/paginaFuncties.php';
require_once '../logica/bestelOverzichtFuncties.php';
require_once '../data/bestellingenData.php';
require_once '../data/gebruikersData.php';

controleerToegang();

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    die("Geen bestelling geselecteerd. Ga terug naar het overzicht.");
}

try {
    $bestellingDetails = haalBestellingDetails($order_id);
    $personeelsleden = haalAllePersoneelsleden();
} catch (Exception $e) {
    die("Fout bij het ophalen van bestellingdetails: " . htmlspecialchars($e->getMessage()));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nieuweStatus = $_POST['status'] ?? $bestellingDetails['status'];
    $nieuweMedewerker = $_POST['personnel_username'] ?? $bestellingDetails['personnel_username'];

    try {
        updateBestellingStatus($order_id, $nieuweStatus);
        updateToegewezenMedewerker($order_id, $nieuweMedewerker);

        header("Location: bestellingDetails.php?order_id=$order_id");
        exit;
    } catch (Exception $e) {
        die("Fout bij het bijwerken van bestellingdetails: " . htmlspecialchars($e->getMessage()));
    }
}

$formattedDateTime = (new DateTime($bestellingDetails['datetime']))->format('Y-m-d H:i');

maakHead();
maakHeader("Bestellingdetails");
?>

    <body>

    <form method="post" action="bestellingDetails.php?order_id=<?= htmlspecialchars($order_id) ?>">
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
                <td><?= htmlspecialchars($formattedDateTime) ?></td>
            </tr>
            <tr>
                <th>Inhoud:</th>
                <td>
                    <ul>
                        <?php foreach ($bestellingDetails['producten'] as $product): ?>
                            <li><?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['quantity']) ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Status:</th>
                <td>
                    <select id="status" name="status">
                        <option value="0" <?= $bestellingDetails['status'] == 0 ? 'selected' : '' ?>>Nieuw</option>
                        <option value="1" <?= $bestellingDetails['status'] == 1 ? 'selected' : '' ?>>In de oven</option>
                        <option value="2" <?= $bestellingDetails['status'] == 2 ? 'selected' : '' ?>>Onderweg</option>
                        <option value="3" <?= $bestellingDetails['status'] == 3 ? 'selected' : '' ?>>Afgeleverd</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Toegewezen Medewerker:</th>
                <td>
                    <select id="personnel_username" name="personnel_username">
                        <?php foreach ($personeelsleden as $personeelslid): ?>
                            <option value="<?= htmlspecialchars($personeelslid['username']) ?>"
                                <?= $bestellingDetails['personnel_username'] == $personeelslid['username'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($personeelslid['username']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <br>
        <button type="submit">Bijwerken</button>
    </form>

    <a href="bestelOverzicht.php">Terug naar het overzicht</a>
    </body>

<?php maakFooter(); ?>