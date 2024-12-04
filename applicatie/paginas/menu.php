<?php
session_start();
require_once '../db_connectie.php';
require_once '../functies/paginaFuncties.php';

$db = maakVerbinding();

$query = "
    SELECT 
        p.name, 
        p.price, 
        pt.name AS type_name
    FROM 
        Product p
    JOIN 
        ProductType pt 
    ON 
        p.type_id = pt.name
    ORDER BY 
        type_name DESC
";
$data = $db->query($query);

$winkelmandjeCount = isset($_SESSION['bestelling']) ? count($_SESSION['bestelling']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $productName = $_POST['product_name'] ?? '';

    if ($action === 'remove_one' && $productName) {
        foreach ($_SESSION['bestelling'] as $key => &$product) {
            if ($product['name'] === $productName) {
                $product['quantity'] -= 1;
                if ($product['quantity'] <= 0) {
                    unset($_SESSION['bestelling'][$key]);
                }
                break;
            }
        }
    } elseif ($action === 'remove_all' && $productName) {
        foreach ($_SESSION['bestelling'] as $key => $product) {
            if ($product['name'] === $productName) {
                unset($_SESSION['bestelling'][$key]);
                break;
            }
        }
    }
}

maakHead();
maakHeader("Menu");
?>

    <body>
    <h2>Winkelmandje</h2>
    <?php if (!empty($_SESSION['bestelling'])): ?>
        <p>Uw winkelmandje bevat <?= $winkelmandjeCount ?> producten:</p>
        <table>
            <thead>
            <tr>
                <th>Product</th>
                <th>Aantal</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['bestelling'] as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['quantity']) ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="action" value="remove_one">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                            <button type="submit">-1</button>
                        </form>
                        <form method="post" action="">
                            <input type="hidden" name="action" value="remove_all">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                            <button type="submit">Verwijder alles</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <form method="get" action="../paginas/bestellen.php">
            <button type="submit">Bestelling afronden</button>
        </form>
    <?php else: ?>
        <p>Uw winkelmandje is leeg.</p>
    <?php endif; ?>

    <h2>Producten</h2>
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
        <?php while ($row = $data->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price'], 2) ?></td>
                <td><?= htmlspecialchars($row['type_name']) ?></td>
                <td>
                    <form method="post" action="../functies/bestelFuncties.php">
                        <input type="hidden" name="action" value="add_to_cart">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit">Voeg toe aan winkelmandje</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </body>

<?php maakFooter(); ?>