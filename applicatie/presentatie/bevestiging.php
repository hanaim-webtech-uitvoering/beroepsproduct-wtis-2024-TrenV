<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../logica/paginaFuncties.php';

// Haal bestellingdetails uit de sessie
$bestellingDetails = $_SESSION['bestelling_details'] ?? null;

// Paginaopmaak starten
maakHead("Bevestiging");
maakHeader("Bevestiging");
?>

    <body>
    <h1>Bedankt voor uw bestelling!</h1>

    <p>Uw bestelling is succesvol geplaatst en wordt zo snel mogelijk verwerkt.</p>

    <?php if ($bestellingDetails): ?>
        <h2>Details van uw bestelling:</h2>
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
        <p>We sturen een bevestiging naar het door u opgegeven adres: <?= htmlspecialchars($bestellingDetails['adres']) ?>.</p>
    <?php else: ?>
        <p>Er zijn geen bestellingdetails beschikbaar. Het lijkt erop dat er iets is misgegaan.</p>
    <?php endif; ?>

    <a href="menu.php">Terug naar het menu</a>
    </body>

<?php
// Sluit de pagina netjes af met de footer
maakFooter();
?>