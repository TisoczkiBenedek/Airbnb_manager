<?php
include './sql_fuggvenyek.php';
function megyebetoltes(){
    $muvelet = "SELECT * FROM megye;";
    $eredmeny = adatokLekerese($muvelet);
    echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
}
function regisztracio(){
    $email = $_POST['email'];
    $jelszo = password_hash($_POST['jelszo'], PASSWORD_DEFAULT);
    $keresztnev = $_POST['knev'];
    $vezeteknev = $_POST['knev'];
    $telefonszam = $_POST['telefon'];
    $tipus = $_POST['tipus'];
    $megye = $_POST['megye'];
    if($tipus== "takarito"){
        $muvelet = "INSERT INTO `felhasznalo`(`emailcim`, `jelszo`, `vezetekNev`, `keresztNev`, `elerhetoseg`, `megyeId`, `tulajdonos`, `takarito`, `admin`) VALUES ('{$email}','{$jelszo}','{$vezeteknev}','{$keresztnev}','{$telefonszam}','{$megye}','false','true','false')";
        return adatokValtoztatasa($muvelet);
    }
    else{
        $muvelet = "INSERT INTO `felhasznalo`(`emailcim`, `jelszo`, `vezetekNev`, `keresztNev`, `elerhetoseg`, `megyeId`, `tulajdonos`, `takarito`, `admin`) VALUES ('{$email}','{$jelszo}','{$vezeteknev}','{$keresztnev}','{$telefonszam}','{$megye}','true','false','false')";
        return adatokValtoztatasa($muvelet);
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