<!--  user story 1 -->
<?php
    require_once '../db_connectie.php';
    $db = maakVerbinding();

    $query = "SELECT p.name, p.price, pt.name AS type_name
    FROM Product p
    JOIN ProductType pt ON p.type_id = pt.name 
    ORDER BY type_name DESC";
    $data = $db->query($query);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pizzeria Sole Machina 🍕</title>
</head>
    <header> Home, Menu, Profiel </header>
  <h1>Menukaart</h1>
  <table>
    <thead>
        <tr>
            <th>Naam</th>
            <th>Prijs</th>
            <th>Type</th>
            
        </tr>
    </thead>
    <tbody>
        <?PHP while ($row = $data->fetch()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['type_name']); ?></td>
                <td><form action="../functies/menufuncties.php" method="post">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
                        <button type="submit">Voeg toe aan bestelling</button>
                    </form> </td>         
                  </tr>
        <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
