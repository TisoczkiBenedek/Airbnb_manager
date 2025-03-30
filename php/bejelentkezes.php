<?php
session_start();
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
    if(!empty($adatok['email']) && isset($adatok['email'])){
        $email = $adatok['email'];
        $muvelet = "SELECT felhasznalo.id, felhasznalo.emailcim, felhasznalo.tulajdonos, felhasznalo.takarito, felhasznalo.jelszo FROM felhasznalo WHERE felhasznalo.emailcim like '{$email}'";
        $eredmeny = adatokLekerese($muvelet);
        if(is_array($eredmeny)){
            if(password_verify($adatok['jelszo'], $eredmeny[0]['jelszo'])){
                $_SESSION['emailcim'] = $eredmeny[0]['emailcim'];//email cím átküldése a főoldalra, a felhasználónév betöltésésnek céljából.
                $_SESSION['id'] = $eredmeny[0]['id'];
                //echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);

                if($eredmeny[0]['tulajdonos'] == 1){//felhasználó típusának ellenőrzése(tulaj)
                    $_SESSION['felhTipus'] = 'tulajdonos';
                } elseif($eredmeny[0]['takarito'] == 1) {
                    $_SESSION['felhTipus'] = 'takarito';
                } else {
                    $_SESSION['felhTipus'] = 'admin';
                }

                // Ellenőrzés
                error_log("Bejelentkezés sikeres: " . print_r($_SESSION, true));

                echo json_encode([ 'emailcim' => $eredmeny[0]['emailcim'], 'felhTipus' => $_SESSION['felhTipus'] ], JSON_UNESCAPED_UNICODE);
            }
            else{
                echo json_encode(["valasz"=>"Hibás jelszó!"], JSON_UNESCAPED_UNICODE);
            }
        }
        else{
            echo json_encode(["valasz"=>"Nem található ilyen e-mail cím és jelszó páros!"], JSON_UNESCAPED_UNICODE);
        }
    }
    else{
        echo json_encode(["valasz"=>"Hiányos adatok!"], JSON_UNESCAPED_UNICODE);
    }
}
?>