<?php function maakHead() {
     echo  <<<HTML
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzeria Sole Machina 🍕</title>
</head>
HTML;
}

function maakHeader($pagina){
    echo  <<<HTML
 <header> Home, Menu, Profiel </header>
  <h1>$pagina</h1>
HTML;

}

function maakFooter(){
    echo  <<<HTML
<footer> Pagina </footer>
HTML;

}
?>