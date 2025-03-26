<?php
session_start();
include 'parameterezett_sql_fuggvenyek.php'; 

$action = $_GET['action'] ?? null;

// Profilnév lekérése
if ($action === 'getProfilNev') {
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
    $email = $_SESSION['emailcim'];

    // Takarítási események betöltése
    $takaritasMuvelet = "SELECT takaritas.id, takaritoErkezes AS start, takaritoTavozas AS end, 'Takarítás' AS title FROM takaritas INNER JOIN felhasznalo ON takaritas.felhasznalo_id = felhasznalo.id WHERE felhasznalo.emailcim = ? AND takaritas.befejezve = 0";
    $takaritasEredmeny = adatokLekerese($takaritasMuvelet, [$email]);

    $events = [];
    foreach ($takaritasEredmeny as $event) {
        try {
            $start = new DateTime($event['start']);
            $end = new DateTime($event['end']);
            $events[] = [
                'id' => $event['id'], 
                'title' => 'Takarítás',
                'start' => $start->format('c'),
                'end' => $end->format('c'),
            ];
        } catch (Exception $e) {
            continue;
        }
    }

    // JSON válasz küldése
    echo json_encode($events, JSON_THROW_ON_ERROR);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
