<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'sql_fuggvenyek.php';

// Mindig JSON választ küldünk
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // JSON adatok feldolgozása
        $data = json_decode(file_get_contents("php://input"), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Érvénytelen JSON adat.");
        }

        $lakasId = $data['lakasId'] ?? null;
        $felhasznaloId = $_SESSION['id'] ?? null;

        // Ellenőrzés: be van-e jelentkezve a felhasználó
        if (!$felhasznaloId) {
            throw new Exception("Felhasználó nincs bejelentkezve.");
        }

        // Lakás és naptár törlése
        $muvelet1 = "DELETE FROM naptarak WHERE lakas_id = $lakasId";
        $valasz1 = adatokValtoztatasa($muvelet1);
        $muvelet2 = "DELETE FROM lakas WHERE id = $lakasId";
        $valasz2 = adatokValtoztatasa($muvelet2);

        if ($valasz2 === "Sikeres művelet!") {
            echo json_encode(["success" => "Lakás sikeresen törölve!", "lakasId" => $lakasId]);
        } else {
            throw new Exception("Hiba történt a lakás törlése során: " . $valasz);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
    exit;
}

// Ha a kérés nem POST
echo json_encode(["error" => "Érvénytelen kérés."]);