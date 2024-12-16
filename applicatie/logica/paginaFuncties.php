<?php


function maakHead($title = "Pizzeria Sole Machina 🍕") {
    echo "<!DOCTYPE html>";
    echo "<html lang='nl'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>$title</title>";
    echo "</head>";
    echo "<body>";
}

function maakHeader($pagina) {
    $isLoggedIn = isset($_SESSION['username']);
    $currentPage = basename($_SERVER['PHP_SELF']);
    $menuItems = [
        "index.php" => "Home",
        "menu.php" => "Menu",
        "profiel.php" => "Profiel",
        "klantLogin.php" => $isLoggedIn ? "Uitloggen" : "Inloggen"
    ];

    echo "<header>";
    echo "<nav class='main-nav'>";
    echo "<ul>";

    foreach ($menuItems as $file => $name) {
        $activeClass = $file === $currentPage ? "class='active'" : "";
        $url = $file === "klantLogin.php" && $isLoggedIn ? "../logica/uitlogFuncties.php" : $file;
        echo "<li><a href='$url' $activeClass>$name</a></li>";
    }

    echo "</ul>";
    echo "</nav>";
    echo "<h1>$pagina</h1>";
    echo "</header>";
}

function maakFooter() {
    echo <<<HTML
<footer>
    <p>&copy; 2024 Pizzeria Sole Machina 🍕</p>
    <a href="privacyverklaring.php">Privacyverklaring</a>
</footer>
</body>
</html>
HTML;
}
?>