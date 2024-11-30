<?php
session_start();
require '../functies/paginaFuncties.php';

maakHead();
maakHeader("Winkelmandje");

$bestelling = $_SESSION['bestelling'] ?? [];
?>

    <body>
    <h1>Winkelmandje</h1>
    <?php if (!empty($bestelling)): ?>
        <table border="1">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Hoeveelheid</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bestelling as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['quantity']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <form action="../functies/bestelFuncties.php" method="post">
            <input type="hidden" name="action" value="checkout">
            <button type="submit">Bestellen</button>
        </form>
    <?php else: ?>
        <p>Uw winkelmandje is leeg.</p>
    <?php endif; ?>
    </body>

<?php maakFooter(); ?>