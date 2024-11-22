<?php
include './sql_fuggvenyek.php';
$teljesURL = explode('/', $_SERVER['REQUEST_URI']);
switch (end($teljesURL)) {
    case 'lekeres':
        lekeres();
        break;
    case 'lekeresid':
        lekeres_id();
        break;
    default:
        # code...
        break;
}
function lekeres(){
    $muvelet = "SELECT lakas.id, lakas.lakcim, lakas.tulajdonosEmail, lakas.kepek, megye.megyeNev FROM lakas INNER JOIN megye on megye.Id = lakas.megyeId;";
    $eredmeny = adatokLekerese($muvelet);
    if(is_array($eredmeny)){
        echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
    }
    else{
        header("BAD REQUEST", true, 400);
        echo json_encode(["valasz"=>"Nincsenek találatok"], JSON_UNESCAPED_UNICODE);
    }
}
function lekeres_id(){
    if($_SERVER['REQUEST_METHOD']== "POST"){
        $adatok = json_decode(file_get_contents('php://input'), true);
        $id = $adatok['id'];
        $muvelet = "SELECT * FROM lakas WHERE lakas.id = $id";
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
        echo json_encode(["valasz"=>"Hibás metódus"], JSON_UNESCAPED_UNICODE);
    }
    
}




?>