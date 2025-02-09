<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    die(json_encode(["valasz" => "Hozzáférés megtagadva! Nincs bejelentkezve."]));
}

include './sql_fuggvenyek.php';

try {
    $muvelet = "SELECT * FROM lakas WHERE felhasznalo_id = {$_SESSION['id']}";
    $eredmeny = adatokLekerese($muvelet);

    if (is_array($eredmeny)) {
        foreach ($eredmeny as &$lakas) {
            if (!empty($lakas['kepek'])) {
                // Ha van kép, használd a kép elérési útját
                $lakas['kepek'] = ["../uploads/" . $lakas['kepek']];
            } else {
                // Ha nincs kép, használj egy alapértelmezett képet
                $lakas['kepek'] = ["../images/default.jpg"];
            }
        }

        echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(["valasz" => $eredmeny], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["valasz" => "Szerverhiba: " . $e->getMessage()]);
}
?>