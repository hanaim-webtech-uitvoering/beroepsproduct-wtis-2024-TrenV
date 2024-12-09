<?php
require_once '../helpers/paginaFuncties.php';
require_once '../logica/menuFuncties.php';

// Haal producten op uit de logica-laag
$productlijst = haalMenuProductenOp();

// Controleer of er een winkelmandje bestaat in de sessie
if (!isset($_SESSION['bestelling'])) {
    $_SESSION['bestelling'] = []; // Zorg ervoor dat het winkelmandje altijd bestaat
}

$winkelmandje = $_SESSION['bestelling'];

maakHead();
maakHeader("Menu");
?>

<body>

<h1>Ons Menu</h1>

<!-- Productenoverzicht -->
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
                    <form method="post" action="../logica/menuFuncties.php">
                        <input type="hidden" name="action" value="add_to_cart">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit">Toevoegen aan winkelmandje</button>
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
                    <form method="post" action="../logica/menuFuncties.php">
                        <input type="hidden" name="action" value="remove_one">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($item['name']) ?>">
                        <button type="submit">Verwijder 1</button>
                    </form>
                    <form method="post" action="../logica/menuFuncties.php">
                        <input type="hidden" name="action" value="remove_all">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($item['name']) ?>">
                        <button type="submit">Verwijder Alles</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <form method="post" action="../logica/menuFuncties.php">
        <input type="hidden" name="action" value="checkout">
        <button type="submit">Afrekenen</button>
    </form>
<?php else: ?>
    <p>Uw winkelmandje is leeg.</p>
<?php endif; ?>

</body>

<?php maakFooter(); ?>