<?php
require_once '../logica/paginaFuncties.php';
require_once '../logica/bestelFuncties.php';

// Controleer of de gebruiker is ingelogd
$isLoggedIn = isset($_SESSION['username']);
$klantGegevens = $isLoggedIn ? haalKlantGegevens($_SESSION['username']) : [];

maakHead();
maakHeader($isLoggedIn ? "Profiel" : "Inloggen");
?>

<body>
<form method="post" action="../logica/bestelFuncties.php">
    <input type="hidden" name="action" value="checkout">

    <!-- Naam invoerveld -->
    <label for="naam">Naam:</label><br>
    <input type="text" id="naam" name="naam" placeholder="Voor- en achternaam"
           value="<?= $isLoggedIn ? htmlspecialchars($klantGegevens['first_name'] . ' ' . $klantGegevens['last_name']) : '' ?>"
        <?= $isLoggedIn ? 'readonly' : 'required' ?>><br><br>

    <!-- Adres invoerveld -->
    <label for="adres">Adres:</label><br>
    <input type="text" id="adres" name="adres" placeholder="Adres"
           value="<?= $isLoggedIn ? htmlspecialchars($klantGegevens['address']) : '' ?>"
        <?= $isLoggedIn ? 'readonly' : 'required' ?>><br><br>

    <!-- Overzicht van het winkelmandje -->
    <label for="bestelling">Bestelling:</label><br>
    <table border="1">
        <thead>
        <tr>
            <th>Product</th>
            <th>Hoeveelheid</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($_SESSION['bestelling'])): ?>
            <?php foreach ($_SESSION['bestelling'] as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['quantity']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">Uw winkelmandje is leeg.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table><br>

    <!-- Bestelling plaatsen knop -->
    <button type="submit">Bestelling plaatsen</button>
</form>
</body>

<?php maakFooter(); ?>