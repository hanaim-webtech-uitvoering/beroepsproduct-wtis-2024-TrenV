<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../logica/paginaFuncties.php';
require_once '../data/gebruikersData.php';
require_once '../data/bestellingenData.php';

if (!isset($_SESSION['username'])) {
    header("Location: klantLogin.php?error=not_logged_in");
    exit;
}

$username = $_SESSION['username'];
$klantGegevens = haalKlantGegevensOp($username);
$bestellingen = haalBestellingenVanKlant($username);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFirstName = $_POST['first_name'] ?? $klantGegevens['first_name'];
    $newLastName = $_POST['last_name'] ?? $klantGegevens['last_name'];
    $newAddress = $_POST['address'] ?? $klantGegevens['address'];

    try {
        updateKlantGegevens($username, $newFirstName, $newLastName, $newAddress);
        $klantGegevens = haalKlantGegevensOp($username);
        $message = "Uw gegevens zijn succesvol bijgewerkt!";
    } catch (Exception $e) {
        $error_message = "Fout bij het bijwerken van gegevens: " . htmlspecialchars($e->getMessage());
    }
}

maakHead();
maakHeader("Profiel");
?>

<body>
<h1>Welkom op uw profielpagina</h1>

<?php if (!empty($message)): ?>
    <p style="color: green;">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<?php if (!empty($error_message)): ?>
    <p style="color: red;">
        <?= htmlspecialchars($error_message) ?>
    </p>
<?php endif; ?>

<h2>Uw gegevens</h2>
<form method="POST">
    <table>
        <tr>
            <th>Gebruikersnaam:</th>
            <td><?= htmlspecialchars($username) ?></td>
        </tr>
        <tr>
            <th>Voornaam:</th>
            <td>
                <input type="text" name="first_name" value="<?= htmlspecialchars($klantGegevens['first_name'] ?? '') ?>" required>
            </td>
        </tr>
        <tr>
            <th>Achternaam:</th>
            <td>
                <input type="text" name="last_name" value="<?= htmlspecialchars($klantGegevens['last_name'] ?? '') ?>" required>
            </td>
        </tr>
        <tr>
            <th>Adres:</th>
            <td>
                <input type="text" name="address" value="<?= htmlspecialchars($klantGegevens['address'] ?? '') ?>" required>
            </td>
        </tr>
    </table>
    <button type="submit">Opslaan</button>
</form>

<h2>Uw bestellingen</h2>
<?php if (!empty($bestellingen)): ?>
    <table border="1">
        <thead>
        <tr>
            <th>Ordernummer</th>
            <th>Inhoud</th>
            <th>Status</th>
            <th>Datum en Tijd</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($bestellingen as $bestelling): ?>
            <?php
            $formattedDateTime = (new DateTime($bestelling['datetime']))->format('Y-m-d H:i');
            $statusLabels = ['Nieuw', 'In de oven', 'Onderweg', 'Afgeleverd'];
            ?>
            <tr>
                <td><?= htmlspecialchars($bestelling['order_id']) ?></td>
                <td><?= htmlspecialchars($bestelling['producten']) ?></td>
                <td><?= htmlspecialchars($statusLabels[$bestelling['status']] ?? 'Onbekend') ?></td>
                <td><?= htmlspecialchars($formattedDateTime) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>U heeft nog geen bestellingen geplaatst.</p>
<?php endif; ?>

<a href="menu.php">Terug naar menu</a>
</body>

<?php maakFooter(); ?>
