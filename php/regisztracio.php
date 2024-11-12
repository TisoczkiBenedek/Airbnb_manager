<?php
include './sql_fuggvenyek.php';
function megyebetoltes(){
    $muvelet = "SELECT * FROM megye;";
    $eredmeny = adatokLekerese($muvelet);
    echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
}
function regisztracio(){
    $adatok = json_decode(file_get_contents('php://input'), true);
    $email = $adatok['email'];
    $jelszo = password_hash($adatok['jelszo'], PASSWORD_DEFAULT);
    $keresztnev = $adatok['knev'];
    $vezeteknev = $adatok['vnev'];
    $telefonszam = $adatok['telefon'];
    $tipus = $adatok['tipus'];
    $megye = $adatok['megye'];
    if($tipus== "takarito"){
        $muvelet = "INSERT INTO `felhasznalo`(`emailcim`, `jelszo`, `vezetekNev`, `keresztNev`, `elerhetoseg`, `megyeId`, `tulajdonos`, `takarito`, `admin`) VALUES ('{$email}','{$jelszo}','{$vezeteknev}','{$keresztnev}','{$telefonszam}','{$megye}','0','1','0')";
        $valasz = adatokValtoztatasa($muvelet);
        echo json_encode(['valasz'=>"{$valasz}"], JSON_UNESCAPED_UNICODE);
    }
    else{
        $muvelet = "INSERT INTO `felhasznalo`(`emailcim`, `jelszo`, `vezetekNev`, `keresztNev`, `elerhetoseg`, `megyeId`, `tulajdonos`, `takarito`, `admin`) VALUES ('{$email}','{$jelszo}','{$vezeteknev}','{$keresztnev}','{$telefonszam}','{$megye}','1','0','0')";
        $valasz = adatokValtoztatasa($muvelet);
        echo json_encode(['valasz'=>"{$valasz}"], JSON_UNESCAPED_UNICODE);
    }
}
$teljesURL = explode('/', $_SERVER['REQUEST_URI']);
switch (end($teljesURL)) {
    case 'megyek':
        megyebetoltes();
        break;
    case 'regisztracio':
        echo regisztracio();
        break;
    default:
        echo "Hiba";
        break;
}
?>