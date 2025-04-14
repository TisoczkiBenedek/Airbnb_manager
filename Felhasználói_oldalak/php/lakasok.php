<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['emailcim'])) {
    http_response_code(401);
    die(json_encode(["valasz" => "Nincs bejelentkezve."]));
}

// SQL függvények betöltése
include './parameterezett_sql_fuggvenyek.php';

// Az action paraméter lekérése (pl. lekeres, getLakas, modositas, getNaptar)
$action = $_GET['action'] ?? null;

// Profilnév lekérése
if ($action === 'getProfilAdat') {
    $email = $_SESSION['emailcim'];
    $felhasznaloNev = "SELECT Vezeteknev AS Vezeteknev, Keresztnev AS Keresztnev, emailcim AS emailcim, elerhetoseg AS elerhetoseg FROM felhasznalo WHERE emailcim = ?";
    $felhAdatok = adatokLekerese($felhasznaloNev, [$email]);

    if (is_array($felhAdatok) && count($felhAdatok) > 0) {
        $eredmeny = [
            'profilNev' => ($felhAdatok[0]['Vezeteknev'] ?? '') . ' ' . ($felhAdatok[0]['Keresztnev'] ?? ''),
            'emailcim' => $felhAdatok[0]['emailcim'] ?? null,
            'elerhetoseg' => $felhAdatok[0]['elerhetoseg'] ?? null,
        ];
    } else {
        $eredmeny = [
            'error' => 'No user found',
            'profilNev' => "Ismeretlen felhasználó",
            'emailcim' => null,
            'elerhetoseg' => null
        ];
    }

    echo json_encode($eredmeny);
    exit;
}

try {
    switch ($action) {
        case 'lekeres':
            // Lakások lekérése a felhasználóhoz
            $muvelet = "SELECT * FROM lakas WHERE felhasznalo_id = ?";
            $eredmeny = adatokLekerese($muvelet, [$_SESSION['id']]);
            
            if (is_array($eredmeny)) {
                foreach ($eredmeny as &$lakas) {
                    // Ha nincs kép megadva, alapértelmezett kép betöltése
                    $lakas['kepek'] = [$lakas['kepek']];
                }
                echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(["valasz" => "Nincsenek lakások"]);
            }
            break;

        case 'getLakas':
            // Egy adott lakás lekérése ID alapján
            $id = $_GET['id'];
            $muvelet = "SELECT * FROM lakas WHERE id = ? AND felhasznalo_id = ?";
            $eredmeny = adatokLekerese($muvelet, [$id, $_SESSION['id']]);
            
            if (is_array($eredmeny) && count($eredmeny) > 0) {
                $lakas = $eredmeny[0];
                // Ha nincs kép megadva, alapértelmezett kép betöltése
                $lakas['kepek'] = [$lakas['kepek'] ?: '../images/default.jpg'];
                echo json_encode($lakas, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(["valasz" => "Lakás nem található"]);
            }
            break;

            case 'modositas':
                // Lakás módosítása
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    try {
                        // Ellenőrizzük, hogy az ID létezik-e
                        if (!isset($_POST['id'])) {
                            throw new Exception("Hiányzó lakás azonosító.");
                        }

            
                        $id = $_POST['id'];
                        $data = [
                            'nev' => $_POST['nev'] ?? '',
                            'cim' => $_POST['cim'] ?? '',
                            'terulet' => (int)($_POST['terulet'] ?? 0),
                            'medence' => isset($_POST['medence']) ? 1 : 0, // Medence állapot kezelése
                            'szauna' => isset($_POST['szauna']) ? 1 : 0,   // Szauna állapot kezelése
                            'megye_id' => (int)($_POST['megye'] ?? 0),
                            'belepesi_adatok' => $_POST['belepesi_adatok'] ?? ''
                        ];
            
                        if(empty($_POST['nev']) || empty($_POST['cim']) || empty($_POST['terulet']) || empty($_POST['megye'])){
                            die(json_encode(['success' => false, 'message' => 'Minden kötelező mezőt ki kell tölteni!']));
                        }
                        // Cím ellenőrzés - már létezik-e másik lakásnál ez a cím (kivéve az aktuálisat)
                        $ellenorzes = "SELECT lakas.cim FROM `lakas` WHERE LOWER(lakas.cim) = LOWER(?) AND id != ?";
                        $eredmeny = adatokLekerese($ellenorzes, [$data['cim'], $id]);

                        if (!empty($eredmeny)) {
                            echo json_encode(['success' => false, 'message' => "Ez a lakcím már szerepel a rendszerünkben."], JSON_UNESCAPED_UNICODE);
                            exit;
                        }
            
                        // 1. RÉGI ADATOK LEKÉRÉSE
                        $muvelet = "SELECT * FROM lakas WHERE id = ?";
                        $eredmeny = adatokLekerese($muvelet, [$id]);
                        $regiAdatok = $eredmeny[0] ?? null;
                        
                        // 2. VÁLTOZÁS ELLENŐRZÉSE
                        $valtozasVan = false;
                        if ($regiAdatok) {
                            if ($regiAdatok['nev'] !== $data['nev'] ||
                                $regiAdatok['cim'] !== $data['cim'] ||
                                $regiAdatok['terulet'] != $data['terulet'] ||
                                $regiAdatok['medence'] != $data['medence'] ||
                                $regiAdatok['szauna'] != $data['szauna'] ||
                                $regiAdatok['megye_id'] != $data['megye_id'] ||
                                $regiAdatok['belepesi_adatok'] !== $data['belepesi_adatok']) {
                                $valtozasVan = true;
                            }
                        }

                        if($regiAdatok['megye_id'] != $data['megye_id']){
                            $megyemodTakTorl = "DELETE FROM `takaritas` WHERE takaritas.lakasId = ?";
                            $eredmeny = adatokValtoztatasa($megyemodTakTorl, [$id]);
                        }

                        if($regiAdatok['cim'] != $data['cim']){
                            $bontottRegiCim = explode(" ", $regiAdatok['cim']);
                            $bontottUjCim = explode(" ", $data['cim']);

                            if($bontottRegiCim[0] != $bontottUjCim[0] || $bontottRegiCim[1] != $bontottUjCim[1]){
                                echo json_encode(["success" => false, "message" => "Irányítószámot, és várost bizonyos okokból módosítani nem lehet"]);
                                exit;
                            }
                        }
                        
                        // 3. KÉP ELLENŐRZÉS
                        $regiKep = $regiAdatok['kepek'] ?? null;
                        $ujKep = $regiKep;
                        
                        if (!empty($_FILES['kepFeltoltes']['name'])) {
                            $valtozasVan = true;
                            $engedett = ['image/jpeg', 'image/jpg', 'image/png', 'image/jfif'];

                            if(!in_array($_FILES['kepFeltoltes']['type'], $engedett)){
                                die(json_encode(['success' => false, 'message' => 'Csak JPG/PNG/JFIF formátum!']));
                            }

                            if (!empty($_FILES['kepFeltoltes']['name'])) {
                                $uploadDir = "../php/uploads/lakas_{$id}/kepek/";
                                if (!file_exists($uploadDir)) {
                                    mkdir($uploadDir, 0755, true);
                                }
                            }

                            $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', $_FILES['kepFeltoltes']['name']);
                            $kepPath = $uploadDir . $fileName;
                            
                            if (!move_uploaded_file($_FILES['kepFeltoltes']['tmp_name'], $kepPath)) {
                                throw new Exception("Képfeltöltés sikertelen.");
                            }

                            if ($regiKep && file_exists($regiKep)) {
                                unlink($regiKep);
                            }
                            $data['kepek'] = $kepPath;
                        }
            
                        // 4. NAPTÁR FRISSÍTÉSE, HA KELL
                        if (!empty($_FILES['naptarFeltoltes']['tmp_name'])) {
                            $icsContent = file_get_contents($_FILES['naptarFeltoltes']['tmp_name']);
                            $muvelet = "INSERT INTO naptarak (lakas_id, file_name, file_content) 
                                        VALUES (?, ?, ?)
                                        ON DUPLICATE KEY UPDATE 
                                        file_name = VALUES(file_name), 
                                        file_content = VALUES(file_content)";
                            $params = [$id, $_FILES['naptarFeltoltes']['name'], $icsContent];
                            adatokValtoztatasa($muvelet, $params);
                        }

                        // 5. HA NINCS VÁLTOZÁS
                        if (!$valtozasVan) {
                            echo json_encode(["success" => true, "message" => "Nem volt módosítás"]);
                            exit;
                        }
            
                        // 5. SQL FRISSÍTÉS
                        $muvelet = "UPDATE lakas SET 
                            nev = ?, 
                            cim = ?, 
                            terulet = ?, 
                            medence = ?, 
                            szauna = ?, 
                            megye_id = ?, 
                            belepesi_adatok = ?" 
                            . (!empty($data['kepek']) ? ", kepek = ?" : "") . 
                            " WHERE id = ? AND felhasznalo_id = ?";
            
                        $params = [
                            $data['nev'],
                            $data['cim'],
                            $data['terulet'],
                            $data['medence'],
                            $data['szauna'],
                            $data['megye_id'],
                            $data['belepesi_adatok'],
                        ];
            
                        if (!empty($data['kepek'])) {
                            $params[] = $data['kepek'];
                        }
            
                        $params[] = $id;
                        $params[] = $_SESSION['id'];
            
                        $eredmeny = adatokValtoztatasa($muvelet, $params);
            
                        if ($eredmeny >= 0) {
                            echo json_encode(["success" => true, "message" => $eredmeny > 0 ? "Sikeres módosítás" : "Nem volt módosítás"]);
                        } else {
                            http_response_code(400);
                            echo json_encode(["success" => false, "message" => "Hiba történt a módosítás során"]);
                        }
                    } catch (Exception $e) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'message' => $e->getMessage()
                        ]);
                    }
                }
                break;

    }
} catch (Exception $e) {
    // Szerverhiba esetén hibaüzenet
    http_response_code(500);
    echo json_encode(["valasz" => "Szerverhiba: " . $e->getMessage()]);
}