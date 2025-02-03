<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include './sql_fuggvenyek.php';

header("Content-Type: application/json; charset=UTF-8");

if (isset($_GET['megyek'])) {
    $muvelet = "SELECT id, megyeNev FROM megye;";
    $eredmeny = adatokLekerese($muvelet);
    if (empty($eredmeny)) {
        echo json_encode(["error" => "Nincsenek megyék az adatbázisban."]);
    } else {
        echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// Lakás feltöltése
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['feltoltes'])) {
    $lakasNev = $_POST['lakasNev'] ?? '';
    $lakcim = $_POST['lakcim'] ?? '';
    $terulet = $_POST['terulet'] ?? '';
    $megyeId = $_POST['megye'] ?? '';
    $lakasAdatok = $_POST['lakasAdatok'] ?? '';
    $medence = isset($_POST['medence']) ? 1 : 0;
    $szauna = isset($_POST['szauna']) ? 1 : 0;
    $felhasznaloId = $_SESSION['id'] ?? null;

    // Ellenőrzés: felhasználó bejelentkezve van-e
    if (!$felhasznaloId) {
        echo json_encode(["error" => "Felhasználó nincs bejelentkezve."]);
        exit;
    }

    // Ellenőrzés: hiányos adatok
    if (!$lakasNev || !$lakcim || !$terulet || !$megyeId || !$lakasAdatok) {
        echo json_encode(['error' => "Hiányos adatok!"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Lakás adatainak beszúrása az adatbázisba
    $muvelet = "INSERT INTO lakas (nev, cim, terulet, medence, szauna, megye_id, belepesi_adatok, kepek, felhasznalo_id) 
                VALUES ('$lakasNev', '$lakcim', $terulet, $medence, $szauna, $megyeId, '$lakasAdatok', '', $felhasznaloId)";
    $valasz = adatokValtoztatasa($muvelet);

    if (strpos($valasz, 'Sikeres művelet!') !== false) {
        // Lakás azonosító lekérése
        $muvelet = "SELECT id FROM lakas WHERE nev = '$lakasNev' AND cim = '$lakcim' ORDER BY id DESC LIMIT 1";
        $eredmeny = adatokLekerese($muvelet);
        $lakasId = $eredmeny[0]['id'] ?? null;

        if ($lakasId) {
            // Lakás mappájának létrehozása
            $lakasMappa = "uploads/lakas_{$lakasId}";
            if (!is_dir($lakasMappa)) {
                mkdir($lakasMappa, 0777, true);
                mkdir("{$lakasMappa}/kepek", 0777, true);
                mkdir("{$lakasMappa}/naptar", 0777, true);
            }

            // Kép feltöltése
            $kepFeltoltes = handleFileUpload($_FILES['kepFeltoltes'], ['image/png', 'image/jpeg'], 5 * 1024 * 1024, "{$lakasMappa}/kepek");
            if (isset($kepFeltoltes['error'])) {
                echo json_encode($kepFeltoltes);
                exit;
            }
            $kepFeltoltes = $kepFeltoltes['success'];

            // Kép elérési útjának frissítése az adatbázisban
            $muvelet = "UPDATE lakas SET kepek = '$lakasMappa/kepek/$kepFeltoltes' WHERE id = $lakasId";
            adatokValtoztatasa($muvelet);

            // Naptár fájl feltöltése (ha van)
            if (!empty($_FILES['naptarFeltoltes']['name'])) {
                $naptarFeltoltes = handleFileUpload($_FILES['naptarFeltoltes'], ['text/calendar'], 5 * 1024 * 1024, "{$lakasMappa}/naptar");

                // Ellenőrizzük a fájl kiterjesztését is
                $fileExtension = strtolower(pathinfo($_FILES['naptarFeltoltes']['name'], PATHINFO_EXTENSION));
                if ($fileExtension !== 'ics') {
                    echo json_encode(['error' => "Csak iCal (.ics) fájlok engedélyezettek."]);
                    exit;
                }

                if (isset($naptarFeltoltes['error'])) {
                    echo json_encode($naptarFeltoltes);
                    exit;
                }
                $naptarFileName = $naptarFeltoltes['success'];

                // Naptár adatainak beszúrása az adatbázisba
                $muvelet = "INSERT INTO naptarak (file_name, file_content, lakas_id) VALUES ('$naptarFileName', '', $lakasId)";
                adatokValtoztatasa($muvelet);
            }

            echo json_encode(["success" => "Lakás sikeresen feltöltve!", "lakasId" => $lakasId]);
        } else {
            echo json_encode(["error" => "Hiba történt a lakás azonosító lekérése során."]);
        }
    } else {
        echo json_encode(["error" => "Hiba történt a lakás feltöltése során: " . $valasz]);
    }
    exit;
}

echo json_encode(["error" => "Érvénytelen kérés."]);

// Fájlfeltöltés kezelése
function handleFileUpload($file, $allowedTypes, $maxFileSize, $targetDir) {
    if (!empty($file['name'])) {
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Fájl kiterjesztés ellenőrzése
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Engedélyezett kiterjesztések
        $allowedExtensions = [];
        foreach ($allowedTypes as $mimeType) {
            if ($mimeType === 'text/calendar') {
                $allowedExtensions[] = 'ics';
            } elseif ($mimeType === 'image/png') {
                $allowedExtensions[] = 'png';
            } elseif ($mimeType === 'image/jpeg') {
                $allowedExtensions[] = 'jpg';
                $allowedExtensions[] = 'jpeg';
            }
        }

        if (!in_array($fileExtension, $allowedExtensions)) {
            return ["error" => "Csak a következő fájltípusok engedélyezettek: " . implode(", ", $allowedTypes)];
        }

        // Fájl méret ellenőrzése
        if ($file['size'] > $maxFileSize) {
            return ["error" => "A fájl mérete túl nagy. Maximális méret: " . ($maxFileSize / 1024 / 1024) . " MB."];
        }

        // Fájl MIME típus ellenőrzése
        $fileType = mime_content_type($file['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            return ["error" => "Csak a következő fájltípusok engedélyezettek: " . implode(", ", $allowedTypes)];
        }

        // Fájl mentése
        $fileName = time() . "_" . basename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $targetDir . "/" . $fileName)) {
            return ["error" => "Hiba történt a fájl feltöltése során."];
        }
        return ["success" => $fileName];
    }
    return ["success" => ""];
}