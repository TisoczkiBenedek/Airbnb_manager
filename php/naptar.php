<?php
include 'sql_fuggvenyek.php';
$action = $_GET['action'] ?? null;

//profilnév betöltése
if ($action === 'getProfilNev') {
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

$email = $_GET['emailcim'] ?? null;

$takaritasBetoltes = "SELECT `id`, `takaritoErkezes` AS start, `takaritoTavozas` AS end, `befejezve`, `megjegyzes` FROM `takaritas` INNER JOIN `felhasznalo` ON felhasznalo.id = felhasznalo_id WHERE felhasznalo.emailcim = ? AND befejezve = 0;";
$takaritasEredmeny = adatokLekerese($takaritasMuvelet, [$email]);


foreach ($takaritasEredmeny as $event) {
    try {
        $start = new DateTime($event['start']);
        $end = new DateTime($event['end']);
        $events[] = [
            'id' => $event['id'],
            'title' => 'Takarítás',
            'start' => $start -> format('c'),
            'end' => $end -> format('c'),
        ];
    } catch (Exception $e) {
        continue;
    }
}