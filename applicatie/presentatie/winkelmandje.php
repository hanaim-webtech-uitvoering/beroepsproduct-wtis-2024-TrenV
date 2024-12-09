<?php
require_once '../helpers/paginaFuncties.php';
require_once '../logica/bestelFuncties.php';

$bestelling = haalWinkelmandjeOp();

maakHead();
maakHeader("Winkelmandje");
?>

<body>

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
                    <form method="post" action="../logica/menuFuncties.php">
                        <input type="hidden" name="action" value="remove_one">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                        <button type="submit">-1</button>
                    </form>
                    <form method="post" action="../logica/menuFuncties.php">
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
    <form method="post" action="../logica/menuFuncties.php">
        <input type="hidden" name="action" value="checkout">
        <button type="submit">Afrekenen</button>
    </form>
<?php else: ?>
    <p>Uw winkelmandje is leeg.</p>
<?php endif; ?>

<a href="menu.php">Terug naar menu</a>
</body>

<?php maakFooter(); ?>