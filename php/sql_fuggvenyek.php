<?php
function adatokLekerese($muvelet){
    //Kapcsolat létrehozása
    $db = new mysqli ('localhost', 'root', '', 'vizsgaremek_takaritas');
    // Kapcsolat létrejött-e
    if($db->connect_errno == 0){
        //Az sql művelet végrehajtása
        $eredmeny = $db->query($muvelet);
        //Történt-e hiba a végrehajtáskor
        if($db->errno == 0){
            //Kaptunk-e vissza adatokat
            if($eredmeny->num_rows !=0){
                //Az adatok lehívása
                $adatok = $eredmeny->fetch_all(MYSQLI_ASSOC);
            }
            else {
                $adatok = array('valasz'=>'Nincsenek találatok!');
            }
        }
        else{
            $adatok= $db->error;
        }
    }
    else{
        $adatok= $db->connect_error;
    }
    return json_encode($adatok, JSON_UNESCAPED_UNICODE);
}
function adatokValtoztatasa($muvelet){
    $db = new mysqli ('localhost', 'root', '', 'vizsgaremek_takaritas');
    if($db->connect_errno==0){
        $db->query($muvelet);
        if($db-> errno==0){
            if($db->affected_rows>0){
                return 'Sikeres művelet!';
            }
            else if($db->affected_rows==0){
                return 'Sikertelen művelet!';
            }
            else{
                return $db->error;
            }
        }
        else{
            return $db->error;
        }
    }
    else{
        return $db->connect_error;
    }
}



?>