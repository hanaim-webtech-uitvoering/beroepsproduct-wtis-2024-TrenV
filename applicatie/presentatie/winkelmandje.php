<?php
require_once '../logica/paginaFuncties.php';
require_once '../logica/bestelFuncties.php';

$bestelling = haalWinkelmandjeOp();

maakHead();
maakHeader("Winkelmandje");
?>

<body>
<h1>Uw Winkelmandje</h1>

<?php if (!empty($bestelling)): ?>
    <table>
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
                    <form method="post" action="../logica/menuFuncties.php" style="display:inline;">
                        <input type="hidden" name="action" value="remove_one">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                        <button type="submit">-1</button>
                    </form>
                    <form method="post" action="../logica/menuFuncties.php" style="display:inline;">
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

    <form method="post" action="../logica/bestelFuncties.php">
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

<?php maakFooter(); ?> var dump toegevoegd, maar na het toevoegen van items wordt er niks gedumpt