<?php
ini_set('display_errors', 0); // Hibák ne jelenjenek meg
error_reporting(0); // Minden hiba jelzés kikapcsolása

require __DIR__ . '/../vendor/autoload.php';
use Sabre\VObject\Reader;

include 'parameterezett_sql_fuggvenyek.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? null;

if ($action === 'getProfilNev') {
    session_start();
    if (!isset($_SESSION['emailcim'])) {
        echo json_encode(['error' => 'Nincs bejelentkezve']);
        exit;
    }

    $email = $_SESSION['emailcim'];
    $felhasznaloNev = "SELECT felhasznalo.Vezeteknev, felhasznalo.Keresztnev FROM `felhasznalo` WHERE felhasznalo.emailcim = ?";
    $nevEredmeny = adatokLekerese($felhasznaloNev, [$email]);

    if (is_array($nevEredmeny) && count($nevEredmeny) > 0) {
        $vezeteknev = $nevEredmeny[0]['Vezeteknev'];
        $keresztnev = $nevEredmeny[0]['Keresztnev'];
        $profilNev = $vezeteknev . ' ' . $keresztnev;
    } else {
        $profilNev = "Ismeretlen felhasználó";
    }

    echo json_encode(['profilNev' => $profilNev]);
    exit;
}

try {
    $lakas_id = $_GET['lakas_id'] ?? null;

    if (!$lakas_id) {
        throw new Exception("Nincs lakas_id megadva");
    }

    if (!is_numeric($lakas_id)) {
        throw new Exception("Érvénytelen lakas_id");
    }

    $muvelet = "SELECT file_content FROM naptarak WHERE lakas_id = " . $lakas_id;
    $eredmeny = adatokLekerese($muvelet);

    if (isset($eredmeny['error'])) {
        throw new Exception($eredmeny['error']);
    }

    if (empty($eredmeny) || empty($eredmeny[0]['file_content'])) {
        throw new Exception("Nincs ICS tartalom ehhez a lakáshoz: " . $lakas_id);
    }

    $icsContent = $eredmeny[0]['file_content'];

    try {
        $vcalendar = Reader::read($icsContent);
        $events = [];
        $ma = new DateTime();

        foreach ($vcalendar->VEVENT as $vevent) {
            $start = $vevent->DTSTART->getDateTime();
            $end = $vevent->DTEND->getDateTime();

            if ($start >= $ma || $end >= $ma) {
                $events[] = [
                    'title' => (string)$vevent->SUMMARY,
                    'start' => $start->format('c'),
                    'end' => $end->format('c'),
                ];
            }
        }
    } catch (Exception $e) {
        throw new Exception("Hiba az ICS tartalom feldolgozásakor: " . $e->getMessage());
    }

    echo json_encode($events, JSON_THROW_ON_ERROR);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>