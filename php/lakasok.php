<?php
include './sql_fuggvenyek.php';
switch ($variable) {
    case 'lekeres':
        lekeres();
        break;
    
    default:
        # code...
        break;
}
function lekeres(){
    $muvelet = "SELECT * FROM lakas";
    $eredmeny = adatokLekerese($muvelet);
    if(is_array($eredmeny)){
        echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
    }
    else{
        header("BAD REQUEST", true, 400);
        echo json_encode(["valasz"=>"Nincsenek találatok"], JSON_UNESCAPED_UNICODE);
    }
}




?>