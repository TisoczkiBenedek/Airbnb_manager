<?php
include './sql_fuggvenyek.php';
include './bejelentkezes.php';

$teljesURL = explode('/', $_SERVER['REQUEST_URI']);
switch (end($teljesURL)) {
    case 'lekeres':
        lekeres();
        break;
    case 'modositas':
        modositas();
        break;
    case 'torles':
        torles();
        break;
    case 'megyek':
        megyebetoltes();
        break;
    default:
        # code...
        break;
}
function lekeres(){
        $muvelet = "SELECT lakas.lakcim, lakas.kepek, megye.megyeNev FROM lakas INNER JOIN megye on megye.Id = lakas.megyeId WHERE lakas.felhasznalo_id =  $_SESSION['id'];";
        $eredmeny = adatokLekerese($muvelet);
        if(is_array($eredmeny)){
            echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
        }
        else{
            header("BAD REQUEST", true, 400);
            echo json_encode(["valasz"=>"Nincsenek találatok"], JSON_UNESCAPED_UNICODE);
        }
}
function modositas(){
    if($_SERVER['REQUEST_METHOD']== 'POST'){
        $adatok = json_decode(file_get_contents('php://input'), true);
        if(!empty($adatok['id']) && !empty($adatok['email']) && isset($adatok['email']) && isset($adatok['id'])){
            $id = $adatok['id'];
            $email = $adatok['email'];
            $muvelet = "UPDATE `lakas` SET `tulajdonosEmail` = '{$email}' WHERE `lakas`.`Id` = $id";
            $eredmeny =adatokValtoztatasa($muvelet);
            echo json_encode(['valasz'=> $eredmeny], JSON_UNESCAPED_UNICODE);
        }
        else{
            header("BAD REQUEST", true, 400);
            echo json_encode(["valasz"=>"Hiányos adatok!"], JSON_UNESCAPED_UNICODE);
        }
    }
    else{
        header("BAD REQUEST", true, 400);
        echo json_encode(["valasz"=>"Hibás metódus"], JSON_UNESCAPED_UNICODE);
    }
    
}
function torles(){
    if($_SERVER['REQUEST_METHOD']== 'DELETE'){
        $adatok = json_decode(file_get_contents('php://input'), true);
        if(isset($adatok['id']) && !empty($adatok['id'])){
            $id = $adatok['id'];
            $muvelet = "DELETE FROM lakas WHERE `lakas`.`Id` = $id";
            $eredmeny = adatokValtoztatasa($muvelet);
            echo json_encode(["valasz"=>$eredmeny], JSON_UNESCAPED_UNICODE);
        }
        else{
            header("BAD REQUEST", true, 400);
            echo json_encode(["valasz"=>"Hiányos adatok!"], JSON_UNESCAPED_UNICODE);
        }
    }
    else{
        header("BAD REQUEST", true, 400);
        echo json_encode(["valasz"=>"Hibás metódus"], JSON_UNESCAPED_UNICODE);
    }
}
function megyebetoltes(){
    $muvelet = "SELECT * FROM megye;";
    $eredmeny = adatokLekerese($muvelet);
    echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
}





?>