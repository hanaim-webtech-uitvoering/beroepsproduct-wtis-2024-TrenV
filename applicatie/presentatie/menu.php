<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../logica/paginaFuncties.php';
require_once '../logica/menuFuncties.php';
require_once '../logica/bestelFuncties.php';

$productlijst = haalMenuProductenOp();
$winkelmandje = haalWinkelmandjeOp();

maakHead("Menu");
maakHeader("Menu");
?>

    <body>
    <h1>Ons Menu</h1>

    <?php if (!empty($productlijst)): ?>
        <table>
            <thead>
            <tr>
                <th>Naam</th>
                <th>Prijs</th>
                <th>Type</th>
                <th>Actie</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($productlijst as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>€<?= number_format($product['price'], 2) ?></td>
                    <td><?= htmlspecialchars($product['type_name']) ?></td>
                    <td>
                        <form method="post" action="../logica/menuFuncties.php" style="display: inline;">
                            <input type="hidden" name="action" value="add_to_cart">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit">Toevoegen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Geen producten beschikbaar.</p>
    <?php endif; ?>

    <!-- Winkelmandje -->
    <h2>Uw Winkelmandje</h2>
    <?php if (!empty($winkelmandje)): ?>
        <table>
            <thead>
            <tr>
                <th>Product</th>
                <th>Hoeveelheid</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($winkelmandje as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td>
                        <form method="post" action="../logica/menuFuncties.php" style="display:inline;">
                            <input type="hidden" name="action" value="remove_one">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($item['name']) ?>">
                            <button type="submit">-1</button>
                        </form>
                        <form method="post" action="../logica/menuFuncties.php" style="display:inline;">
                            <input type="hidden" name="action" value="remove_all">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($item['name']) ?>">
                            <button type="submit">Verwijder alles</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <br>

        <form method="post" action="../logica/menuFuncties.php">
            <input type="hidden" name="action" value="checkout">
            <label for="naam">Naam:</label><br>
            <input type="text" id="naam" name="naam" placeholder="Uw naam" required><br><br>
            <label for="adres">Adres:</label><br>
            <input type="text" id="adres" name="adres" placeholder="Uw adres" required><br><br>
            <button type="submit">Afrekenen</button>
        </form>
    <?php else: ?>
        <p>Uw winkelmandje is leeg.</p>
    <?php endif; ?>

    <a href="menu.php">Terug naar menu</a>
    </body>
<?php maakFooter(); ?>