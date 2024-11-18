<?php
include './sql_fuggvenyek.php';
$teljesURL = explode('/', $_SERVER['REQUEST_URI']);
switch (end($teljesURL)) {
    case 'bejelentkezes':
        echo bejelentkezes();
        break;
    
    default:
        echo "HIBA";
        break;
}
function bejelentkezes(){
    $adatok = json_decode(file_get_contents('php://input'), true);
    $email = $adatok['email'];
    $muvelet = "SELECT felhasznalo.emailcim, felhasznalo.tulajdonos, felhasznalo.takarito, felhasznalo.jelszo FROM felhasznalo WHERE felhasznalo.emailcim like '{$email}'";
    $eredmeny = adatokLekerese($muvelet);
    $_SESSION['emailcim'] = $eredmeny[0]['emailcim'];//email cím átküldése a főoldalra, a felhasználónév betöltésésnek céljából.
    header('Location: adminFoOldal.php');//átküldés az admin oldalra
    if(is_array($eredmeny)){
        if(password_verify($adatok['jelszo'], $eredmeny[0]['jelszo'])){
            echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
        }
        else{
            echo json_encode(["valasz"=>"Hibás jelszó!"], JSON_UNESCAPED_UNICODE);
        }
    }
    else{
        echo json_encode(["valasz"=>"Nem található ilyen emil cím és jelszó páros!"], JSON_UNESCAPED_UNICODE);
    }
}
?>