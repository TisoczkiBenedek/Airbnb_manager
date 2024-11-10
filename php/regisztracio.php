<?php
include './sql_fuggvenyek.php';
$teljesURL = explode('/', $_SERVER['REQUEST_URI']);
switch (end($teljesURL)) {
    case 'megyek':
        $muvelet = "SELECT * FROM megye;";
        echo adatokLekerese($muvelet);
        break;
    default:
        echo "Hiba";
        break;
}
?>