<?php
ini_set('display_errors', 1); // Hibák megjelenítése fejlesztés közben
error_reporting(E_ALL); // Minden hiba jelzés bekapcsolása
date_default_timezone_set('Europe/Budapest');


require __DIR__ . '/../vendor/autoload.php';
use Sabre\VObject\Reader;

include 'parameterezett_sql_fuggvenyek.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? null;


//esemény törlése
if($action === 'deleteTakaritas'){
    session_start();

    $json = file_get_contents('php://input');
    $adat = json_decode($json, true);
    $eventId = $adat['eventId'];

    try {
        $muvelet = "DELETE FROM takaritas WHERE id = ?";
        $eredmeny = adatokValtoztatasa($muvelet, [$eventId]);
        echo json_encode(['success' => $eredmeny]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

//esemény módosítása
if($action === 'updateTakaritas'){
    session_start();
    $json = file_get_contents('php://input');
    $adat = json_decode($json, true);

    if(empty($adat['id']) || empty($adat['start']) || empty($adat['end']) || empty($adat['takarito_id'])) {
        echo json_encode((['success' => false, 'error' => 'Hiányzó adatok']));
        exit;
    }

    $esemenyId = $adat['id'];
    // Expliciten megadjuk az UTC időzónát a DateTime objektumok létrehozásakor
    $kezdoDatum = new DateTime($adat['start'], new DateTimeZone('UTC'));
    $vegDatum = new DateTime($adat['end'], new DateTimeZone('UTC'));
    $takaritoId = $adat['takarito_id'];

    try {
        $muvelet = "UPDATE takaritas SET takaritoErkezes = ?, takaritoTavozas = ?, felhasznalo_id = ? WHERE id = ?";
        // Az adatbázisban UTC formátumban frissítjük
        $frissites = [$kezdoDatum->format('Y-m-d H:i:s'), $vegDatum->format('Y-m-d H:i:s'), $takaritoId, $esemenyId];
        $eredmeny = adatokValtoztatasa($muvelet, $frissites);

        if($eredmeny) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Sikertelen módosítás']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

//takarítók betöltése
if ($action === 'getTakaritok') {
    $megyeId = (int)$_GET['megye_id'] ?? null;

    try {
        $muvelet = "SELECT id, CONCAT(Vezeteknev, ' ', Keresztnev) AS nev FROM felhasznalo WHERE megyeid = ? AND takarito = 1 AND (TakaritoSzabadsagKezd >= NOW() OR TakaritoSzabadsagVeg <= NOW());";
        $takaritok = adatokLekerese($muvelet, [$megyeId]);

        header('Content-Type: application/json');
        if(empty($takaritok)){
            echo json_encode(["valasz" => "Ebben a megyében nincsenek takarítóink"]);
            exit;
        }
        echo json_encode($takaritok);
        exit; // Kilépés a kódból, ne futtassa le a többi részt
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

 // Takarító ID-jának lekérése esemény ID alapján
 if ($action === 'getTakaritoId') {
        $eventId = $_GET['event_id'] ?? null;
  
        if ($eventId) {
            try {
                $muvelet = "SELECT felhasznalo_id FROM takaritas WHERE id = ?";
                $eredmeny = adatokLekerese($muvelet, [$eventId]);
  
                if ($eredmeny && count($eredmeny) > 0) {
                    echo json_encode(['success' => true, 'takarito_id' => $eredmeny[0]['felhasznalo_id']]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Nem található esemény a megadott ID-val.']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Hiányzó esemény ID.']);
        }
        exit;
    }

//profilnév betöltése
if ($action === 'getProfilAdat') {
    session_start();

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

//esemény mentése
if ($action === 'esemenyMentes') {
    session_start();
    $json = file_get_contents('php://input');
    $adat = json_decode($json, true);

    if (empty($adat['start']) || empty($adat['end']) || empty($adat['lakas_id']) || empty($adat['takarito_id'])) {
        echo json_encode(['success' => false, 'error' => 'Hiányzó adatok']);
        exit;
    }

    try {
        // Expliciten megadjuk az UTC időzónát a DateTime objektumok létrehozásakor
        $kezdoDatum = new DateTime($adat['start'], new DateTimeZone('UTC'));
        $vegDatum = new DateTime($adat['end'], new DateTimeZone('UTC'));
        $datum = $kezdoDatum->format('Y-m-d');

        //van e már takarító rendelve erre a napra
        $rendelesEll = "SELECT id FROM takaritas WHERE lakasId = ? AND DATE(takaritoErkezes) = ?";
        $ellenorzes = [$adat['lakas_id'], $datum];
        $letezoEsemeny = adatokLekerese($rendelesEll, $ellenorzes);

        if(!empty($letezoEsemeny)){
            echo json_encode(['succes' => false, 'error' => 'Erre a napra már van takarítás rendelve']);
            exit;
        }

        //esemény mentése adatbázisba
        $felvitel = "INSERT INTO takaritas (lakasId, felhasznalo_id, takaritoErkezes, takaritoTavozas, befejezve) VALUES (?, ?, ?, ?, 0)";
        // Az adatbázisba UTC formátumban mentjük
        $mentes = [$adat['lakas_id'], $adat['takarito_id'], $kezdoDatum->format('Y-m-d H:i:s'), $vegDatum->format('Y-m-d H:i:s')];

        $mentesEredmeny = adatokValtoztatasa($felvitel, $mentes);

        if($mentesEredmeny) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Adatbázis hiba']);
        }
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

try {
    $lakas_id = $_GET['lakas_id'] ?? null;

    // ICS események betöltése
    $muvelet = "SELECT file_content FROM naptarak WHERE lakas_id = ?";
    $eredmeny = adatokLekerese($muvelet, [$lakas_id]);

    $events = [];
    if (!empty($eredmeny) && !empty($eredmeny[0]['file_content'])) {
        $icsContent = $eredmeny[0]['file_content'];
        $vcalendar = Reader::read($icsContent);
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
    }

    // Takarítási események betöltése
// Takarítási események betöltése
$takaritasMuvelet = "SELECT id, takaritoErkezes AS start, takaritoTavozas AS end, 'Takarítás' AS title, felhasznalo_id FROM takaritas WHERE lakasId = ? AND befejezve = 0";
$takaritasEredmeny = adatokLekerese($takaritasMuvelet, [$lakas_id]);

    foreach ($takaritasEredmeny as $event) {
        try {
            $start = new DateTime($event['start']);
            $end = new DateTime($event['end']);
            $events[] = [
                'id' => $event['id'],
                'title' => 'Takarítás',
                'start' => $start->format('c'),
                'end' => $end->format('c'),
                'extendedProps' => ['takarito_id' => $event['felhasznalo_id']], // Hozzáadva
            ];
        } catch (Exception $e) {
            continue;
        }
    }

    echo json_encode($events, JSON_THROW_ON_ERROR);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>