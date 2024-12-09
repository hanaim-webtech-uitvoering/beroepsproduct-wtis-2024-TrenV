<?php
require_once '../helpers/paginaFuncties.php';
require_once '../logica/bestelOverzichtFuncties.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['klantgegevens'])) {
    header("Location: klantLogin.php?error=not_logged_in");
    exit;
}

// Haal klantgegevens en bestellingen op via de logica-laag
$klantGegevens = haalKlantGegevensOp();
$bestellingen = haalBestellingenOp();

maakHead();
maakHeader("Profiel");
?>

<body>
    <h1>Welkom op uw profielpagina</h1>

    <h2>Uw gegevens</h2>
    <table>
        <tr>
            <th>Naam:</th>
            <td><?= htmlspecialchars($klantGegevens['name'] ?? 'Onbekend') ?></td>
        </tr>
        <tr>
            <th>Adres:</th>
            <td><?= htmlspecialchars($klantGegevens['address'] ?? 'Onbekend') ?></td>
        </tr>
    </table>

    <h2>Uw bestellingen</h2>
    <?php if (!empty($bestellingen)): ?>
        <table>
            <thead>
                <tr>
                    <th>Ordernummer</th>
                    <th>Inhoud</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bestellingen as $bestelling): ?>
                    <tr>
                        <td><?= htmlspecialchars($bestelling['order_id']) ?></td>
                        <td><?= htmlspecialchars($bestelling['products']) ?></td>
                        <td><?= htmlspecialchars(vertaalStatus($bestelling['status'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>U heeft nog geen bestellingen geplaatst.</p>
    <?php endif; ?>

    <a href="menu.php">Terug naar menu</a>
</body>

<?php maakFooter(); ?>