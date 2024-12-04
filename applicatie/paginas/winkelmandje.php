<?php
session_start();
require '../functies/paginaFuncties.php';

maakHead();
maakHeader("Winkelmandje");

// Haal de bestelling op uit de sessie
$bestelling = $_SESSION['bestelling'] ?? [];
?>

    <body>
    <h1>Uw Winkelmandje</h1>

    <?php if (!empty($bestelling)): ?>
        <table border="1">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Hoeveelheid</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bestelling as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['quantity']) ?></td>
                    <td>
                        <!-- Verwijder één product -->
                        <form method="post" action="../functies/bestelFuncties.php">
                            <input type="hidden" name="action" value="remove_one">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                            <button type="submit">-1</button>
                        </form>

                        <!-- Verwijder alle producten -->
                        <form method="post" action="../functies/bestelFuncties.php">
                            <input type="hidden" name="action" value="remove_all">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                            <button type="submit">Verwijder alles</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <!-- Ga naar afrekenen -->
        <form method="post" action="../functies/bestelFuncties.php">
            <input type="hidden" name="action" value="checkout">
            <button type="submit">Bestelling Afronden</button>
        </form>
    <?php else: ?>
        <p>Uw winkelmandje is leeg.</p>
    <?php endif; ?>
    </body>

<?php maakFooter(); ?>