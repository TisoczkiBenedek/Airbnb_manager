<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
use Sabre\VObject\Reader;

include 'sql_fuggvenyek.php';

header('Content-Type: application/json');

try {
    // Lakas_id kiolvasása a kérésből
    $lakas_id = $_GET['lakas_id'] ?? null;

    if (!$lakas_id) {
        throw new Exception("Nincs lakas_id megadva");
    }

    // Ellenőrizd, hogy a lakas_id szám-e
    if (!is_numeric($lakas_id)) {
        throw new Exception("Érvénytelen lakas_id");
    }

    // ICS tartalom lekérése az adatbázisból
    $muvelet = "SELECT file_content FROM naptarak WHERE lakas_id = " . $lakas_id;
    $eredmeny = adatokLekerese($muvelet);

    if (isset($eredmeny['error'])) {
        throw new Exception($eredmeny['error']);
    }

    if (empty($eredmeny) || empty($eredmeny[0]['file_content'])) {
        throw new Exception("Nincs ICS tartalom ehhez a lakáshoz: " . $lakas_id);
    }

    $icsContent = $eredmeny[0]['file_content'];

    // ICS tartalom feldolgozása
    try {
        $vcalendar = Reader::read($icsContent);
        $events = [];

        foreach ($vcalendar->VEVENT as $vevent) {
            $events[] = [
                'title' => (string)$vevent->SUMMARY,
                'start' => $vevent->DTSTART->getDateTime()->format('c'), // ISO 8601 formátum
                'end' => $vevent->DTEND->getDateTime()->format('c'),
            ];
        }
    } catch (Exception $e) {
        throw new Exception("Hiba az ICS tartalom feldolgozásakor: " . $e->getMessage());
    }

    // JSON válasz küldése
    echo json_encode($events, JSON_THROW_ON_ERROR);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>