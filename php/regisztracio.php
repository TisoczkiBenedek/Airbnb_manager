<?php
include './sql_fuggvenyek.php';
$teljesURL = explode('/', $_SERVER['REQUEST_URI']);
switch (end($teljesURL)) {
    case 'megyek':
        $muvelet = "SELECT * FROM megye;";
        echo adatokLekerese($muvelet);
        break;
    case 'regisztracio':
        $email = $_POST['email'];
        $jelszo = $_POST['jelszo'];
        $keresztnev = $_POST['knev'];
        $vezeteknev = $_POST['knev'];
        $telefonszam = $_POST['telefon'];
        $tipus = $_POST['tipus'];
        $megye = $_POST['megye'];
        if($tipus== "takarito"){
            $muvelet = "INSERT INTO `felhasznalo`(`emailcim`, `jelszo`, `vezetekNev`, `keresztNev`, `elerhetoseg`, `megyeId`, `tulajdonos`, `takarito`, `admin`) VALUES ('{$email}','{$jelszo}','{$vezeteknev}','{$keresztnev}','{$telefonszam}','{$megye}','false','true','false')";
            echo adatokValtoztatasa($muvelet);
        }
        else{
            $muvelet = "INSERT INTO `felhasznalo`(`emailcim`, `jelszo`, `vezetekNev`, `keresztNev`, `elerhetoseg`, `megyeId`, `tulajdonos`, `takarito`, `admin`) VALUES ('{$email}','{$jelszo}','{$vezeteknev}','{$keresztnev}','{$telefonszam}','{$megye}','true','false','false')";
            echo adatokValtoztatasa($muvelet);
        }
        break;
    default:
        echo "Hiba";
        break;
}
?>