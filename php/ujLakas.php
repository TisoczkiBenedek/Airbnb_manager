<?php
session_start();
error_reporting(E_ALL);


include './parameterezett_sql_fuggvenyek.php';
header("Content-Type: application/json; charset=UTF-8"); // JSON válasz beállítá

// Fájlfeltöltés kezelése
function handleFileUpload($file, $allowedExtensions, $maxFileSize, $targetDir) {
    if (!isset($file) || empty($file['name'])) {
        return ["error" => "Nem érkezett fájl a feltöltéshez."];
    }

    if (!isset($file['tmp_name']) || !is_string($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ["error" => "A fájl nem töltődött fel megfelelően. Hibakód: " . $file['error']];
    }
    
    if (!empty($file['name'])) {
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Fájl kiterjesztés ellenőrzése
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            return ["error" => "Csak a következő fájltípusok engedélyezettek: " . implode(", ", $allowedExtensions)];
        }

        // Fájl méret ellenőrzése
        if ($file['size'] > $maxFileSize) {
            return ["error" => "A fájl mérete túl nagy. Maximális méret: " . ($maxFileSize / 1024 / 1024) . " MB."];
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

try {
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['feltoltes'])) {
        // Kötelező mezők ellenőrzése
        $lakasNev = $_POST['lakasNev'] ?? '';
        $lakcim = $_POST['lakcim'] ?? '';
        $terulet = $_POST['terulet'] ?? '';
        $megyeId = $_POST['megye'] ?? '';
        $lakasAdatok = $_POST['lakasAdatok'] ?? '';
        $medence = isset($_POST['medence']) ? 1 : 0;
        $szauna = isset($_POST['szauna']) ? 1 : 0;
        $felhasznaloId = $_SESSION['id'] ?? null;

        if (empty($lakasNev) || empty($lakcim) || empty($terulet) || empty($megyeId) || empty($lakasAdatok)) {
            echo json_encode(['error' => "Kérem töltse ki az összes kötelező mezőt!"], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // További feldolgozás...
        try {
            $ellenorzes = "SELECT lakas.cim FROM `lakas` WHERE LOWER(lakas.cim) = LOWER(?);";
            $params = [trim($lakcim)];
            $check = adatokLekerese($ellenorzes, $params);

            if (!empty($check)) {
                echo json_encode(['error' => "Ez a lakcím már szerepel a rendszerünkben."], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $muvelet = "INSERT INTO lakas (nev, cim, terulet, medence, szauna, megye_id, belepesi_adatok, kepek, felhasznalo_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [$lakasNev, $lakcim, $terulet, $medence, $szauna, $megyeId, $lakasAdatok, '', $felhasznaloId];
            $valasz = adatokValtoztatasa($muvelet, $params);

            if (strpos($valasz, 'Sikeres művelet!') !== false) {
                $muvelet = "SELECT id FROM lakas WHERE nev = '$lakasNev' AND cim = '$lakcim' ORDER BY id DESC LIMIT 1";
                $eredmeny = adatokLekerese($muvelet);
                $lakasId = $eredmeny[0]['id'] ?? null;

                if ($lakasId) {
                    // ABSZOLÚT ÚTVONAL
                    $lakasMappa = __DIR__ . "/uploads/lakas_{$lakasId}";
                
                    // Mappa létrehozása (hibakezeléssel)
                    if (!is_dir($lakasMappa)) {
                        if (!mkdir($lakasMappa, 0755, true)) {
                            echo json_encode(["error" => "Nem sikerült létrehozni a mappát!"]);
                            exit;
                        }
                        if (!mkdir("{$lakasMappa}/kepek", 0755, true)) {
                            echo json_encode(["error" => "Nem sikerült létrehozni a képek mappát!"]);
                            exit;
                        }
                    }
                
                    // Kép feltöltés
                    $kepFeltoltes = handleFileUpload($_FILES['kepFeltoltes'], ['png', 'jpg', 'jpeg'], 5 * 1024 * 1024, "{$lakasMappa}/kepek");
                    if (isset($kepFeltoltes['error'])) {
                        echo json_encode($kepFeltoltes);
                        exit;
                    }
                    $kepFeltoltes = $kepFeltoltes['success'];
                
                    // Kép útvonal frissítése (paraméterezett lekérdezés)
                    $muvelet = "UPDATE lakas SET kepek = ? WHERE id = ?";
                    $kepPath = "../php/uploads/lakas_{$lakasId}/kepek/{$kepFeltoltes}";
                    adatokValtoztatasa($muvelet, [$kepPath, $lakasId]);
                
                    // Naptár feltöltés
                    if (!empty($_FILES['naptarFeltoltes']['name'])) {
                        $naptarFeltoltes = handleFileUpload($_FILES['naptarFeltoltes'], ['ics'], 5 * 1024 * 1024, "{$lakasMappa}/naptar");
                        if (isset($naptarFeltoltes['error'])) {
                            echo json_encode($naptarFeltoltes);
                            exit;
                        }
                        $naptarFileName = $naptarFeltoltes['success'];
                        $naptarFilePath = "{$lakasMappa}/naptar/{$naptarFileName}";
                
                        // Fájl létezésének ellenőrzése
                        if (!file_exists($naptarFilePath)) {
                            echo json_encode(["error" => "A naptár fájl nem található!"]);
                            exit;
                        }
                        $naptarFileContent = file_get_contents($naptarFilePath);
                
                        // Naptár beszúrása (paraméterezett lekérdezés)
                        $muvelet = "INSERT INTO naptarak (file_name, file_content, lakas_id, felhasznalo_id) VALUES (?, ?, ?, ?)";
                        adatokValtoztatasa($muvelet, [$naptarFileName, $naptarFileContent, $lakasId, $felhasznaloId]);
                    }
                
                    echo json_encode(["success" => "Lakás sikeresen feltöltve!", "lakasId" => $lakasId]);
                    exit;
                } else {
                    echo json_encode(["error" => "Hiba történt a lakás azonosító lekérése során."]);
                    exit;
                }
            } else {
                echo json_encode(["error" => "Hiba történt a lakás feltöltése során: " . $valasz]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Hiba történt: " . $e->getMessage()]);
        }
    }

    echo json_encode(["error" => "Érvénytelen kérés."]);
} catch (Exception $e) {
    echo json_encode(["error" => "Hiba történt: " . $e->getMessage()]);
}