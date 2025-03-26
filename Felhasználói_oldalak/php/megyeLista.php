<?php
header('Content-Type: application/json');
include './parameterezett_sql_fuggvenyek.php';

try {
    $eredmeny = adatokLekerese("SELECT id, megyeNev FROM megye ORDER BY megyeNev");
    echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Hiba a megyék betöltésekor: " . $e->getMessage()]);
}
?>