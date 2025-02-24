<?php
include './sql_fuggvenyek.php';
$teljesURL = explode('/', $_SERVER['REQUEST_URI']);
switch (end($teljesURL)) {
    case 'lekeres':
        lekeres();
        break;
    case 'mentes':
        mentes();
        break;
    case 'megyek':
        megyebetoltes();
        break;
    case 'eszkoz':
        eszkozleker();
        break;
    case 'eszkozIgeny':
        eszkozIgenyHoz();
        break;
    default:
        # code...
        break;
}
function lekeres(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $adatok = json_decode(file_get_contents('php://input'), true);
        if(!empty($adatok['id'])){
            $id = $adatok['id'];
            $muvelet = "SELECT takaritas.id, takaritas.lakasId, takaritas.felhasznalo_id, takaritas.takaritoErkezes, takaritas.befejezve, takaritas.megjegyzes, lakas.nev, lakas.cim, lakas.terulet, lakas.medence, lakas.szauna, lakas.belepesi_adatok, felhasznalo.elerhetoseg FROM takaritas inner join lakas on lakas.id = takaritas.lakasid inner join felhasznalo on felhasznalo.id = lakas.felhasznalo_id WHERE takaritas.felhasznalo_id = $id and DATE(takaritas.takaritoErkezes) = DATE(NOW()) and takaritas.befejezve = 0;";
            $eredmeny = adatokLekerese($muvelet);
            if(is_array($eredmeny)){
                echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
            }
            else{
                header("BAD REQUEST", true, 400);
                echo json_encode(["valasz"=>"Nincsenek találatok"], JSON_UNESCAPED_UNICODE);
            }
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
function mentes(){
    if($_SERVER['REQUEST_METHOD']== 'PUT'){
        $adatok = json_decode(file_get_contents('php://input'), true);
        if( !empty("id") ){
            $id = $adatok['id'];
            $megjegyzes = $adatok["megjegyzes"];
            
            //$muvelet = "SELECT felhasznalo.id FROM felhasznalo WHERE felhasznalo.emailcim = '{$email}'";
            //$valasz= adatokLekerese($muvelet);
            //echo $valasz;
            //$id = $valasz[0]['id'];
            $muvelet = "UPDATE `takaritas` SET `befejezve` = '1', `megjegyzes`= '$megjegyzes', `takaritoTavozas`= NOW() WHERE `takaritas`.`id` = '{$id}'";
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
function megyebetoltes(){
    $muvelet = "SELECT * FROM megye;";
    $eredmeny = adatokLekerese($muvelet);
    echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
}
function eszkozleker(){
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $muvelet = "SELECT * FROM `eszkoz`";
        $eredmeny = adatokLekerese($muvelet);
        echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
    }
}
function eszkozIgenyHoz(){
    if($_SERVER["REQUEST_METHOD"]== "POST"){
        $erekezett = json_decode(file_get_contents('php://input'), true);
        echo json_encode(["valasz"=>"Megérekezett"], JSON_UNESCAPED_UNICODE);
    }
}





?>