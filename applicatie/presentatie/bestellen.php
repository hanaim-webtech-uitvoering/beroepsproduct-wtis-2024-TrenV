<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../logica/paginaFuncties.php';

$bestellingDetails = $_SESSION['bestelling_afgerond_details'] ?? null;

maakHead("Bestelling Voltooid");
maakHeader("Bestelling Voltooid");
?>

    <body>
    <h1>Bedankt voor uw bestelling!</h1>

    <?php if ($bestellingDetails): ?>
        <h2>Bestelgegevens</h2>
        <p><strong>Naam:</strong> <?= htmlspecialchars($bestellingDetails['naam']) ?></p>
        <p><strong>Adres:</strong> <?= htmlspecialchars($bestellingDetails['adres']) ?></p>
        <p><strong>Ordernummer:</strong> <?= htmlspecialchars($bestellingDetails['order_id']) ?></p>
        <h3>Producten:</h3>
        <table border="1">
            <thead>
            <tr>
                <th>Product</th>
                <th>Aantal</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bestellingDetails['producten'] as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['quantity']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Er is geen bestelling om te tonen. Ga terug naar het menu en probeer opnieuw.</p>
    <?php endif; ?>

    <a href="menu.php">Terug naar menu</a>
    </body>

<?php maakFooter(); ?>