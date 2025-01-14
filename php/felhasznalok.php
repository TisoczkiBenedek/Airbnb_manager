<?php
include './sql_fuggvenyek.php';
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
    $muvelet = "SELECT felhasznalo.emailcim, felhasznalo.id, felhasznalo.vezetekNev, felhasznalo.keresztNev, felhasznalo.elerhetoseg, megye.megyeNev FROM felhasznalo INNER JOIN megye on megye.Id = felhasznalo.megyeId;";
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
        if(!empty($adatok['email']) && isset($adatok['email'])){
            $email = $adatok['email'];
            $muvelet = "SELECT felhasznalo.id FROM felhasznalo WHERE felhasznalo.emailcim = $email";
            $valasz= adatokLekerese($muvelet);
            echo $valasz;
            //$id = $valasz[0]['id'];
            //$muvelet = "UPDATE `felhasznalo` SET `emailcim` = '{$email}' WHERE `felhasznalo`.`id` = '{$id}'";
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
            $muvelet = "DELETE FROM felhasznalo WHERE `felhasznalo`.`emailcim` = '$id'";
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