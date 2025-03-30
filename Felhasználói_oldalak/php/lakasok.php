<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    die(json_encode(["valasz" => "Nincs bejelentkezve."]));
}

// SQL függvények betöltése
include './parameterezett_sql_fuggvenyek.php';

$action = $_GET['action'] ?? null;
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

        case 'getProfilAdatok':
            $email = $_SESSION['emailcim'];
            $muvelet = "SELECT CONCAT(Vezeteknev, ' ', Keresztnev) as nev, emailcim as email FROM felhasznalo WHERE id = ?";
            $eredmeny = adatokLekerese($muvelet, [$_SESSION['id']]);
            
            if(is_array($eredmeny)) {
                echo json_encode([
                    'success' => true,
                    'nev' => $eredmeny[0]['nev'],
                    'email' => $eredmeny[0]['email']
                ]);
            }else{
                echo json_encode(['success' => false]);
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
        
                    // 1. LAKCÍM ELLENŐRZÉS - MÓDOSÍTÁSKOR
                    $ellenorzes = "SELECT lakas.cim FROM `lakas` WHERE LOWER(lakas.cim) = LOWER(?) AND id != ?";
                    $params = [trim($data['cim']), $id];
                    $check = adatokLekerese($ellenorzes, $params);
        
                    if (!empty($check)) {
                        echo json_encode(['success' => false, 'message' => "Ez a lakcím már szerepel a rendszerünkben."]);
                        exit;
                    }
        
                    // 2. RÉGI KÉP ELÉRÉSI ÚTJÁNAK LEKÉRÉSE
                    $muvelet = "SELECT kepek FROM lakas WHERE id = ?";
                    $eredmeny = adatokLekerese($muvelet, [$id]);
                    $regiKep = $eredmeny[0]['kepek'] ?? null;
        
                    // 3. RÉGI KÉP TÖRLÉSE, HA LÉTEZIK ÉS ÚJ KÉP VAN FELTÖLTVE
                    if ($regiKep && file_exists($regiKep) && !empty($_FILES['kepFeltoltes']['name'])) {
                        unlink($regiKep);
                    }
        
                    // 4. ÚJ KÉP FELTÖLTÉSE
                    if (!empty($_FILES['kepFeltoltes']['name'])) {
                        $uploadDir = "../php/uploads/lakas_$id/kepek/";
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        $fileName = uniqid() . '_' . basename($_FILES['kepFeltoltes']['name']);
                        move_uploaded_file($_FILES['kepFeltoltes']['tmp_name'], $uploadDir . $fileName);
                        $data['kepek'] = $uploadDir . $fileName;
                    } else {
                        // Ha nincs új kép feltöltve, megtartjuk a régi képet
                        $data['kepek'] = $regiKep;
                    }
        
                    // 5. NAPTÁR FRISSÍTÉSE, HA KELL
                    if (!empty($_FILES['naptarFeltoltes']['tmp_name'])) {
                        $icsContent = file_get_contents($_FILES['naptarFeltoltes']['tmp_name']);
                        $muvelet = "UPDATE naptarak SET file_name = ?, file_content = ? WHERE lakas_id = ?";
                        $params = [$_FILES['naptarFeltoltes']['name'], $icsContent, $id];
                        adatokValtoztatasa($muvelet, $params);
                    }
        
                    // 6. SQL FRISSÍTÉS
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
                        $data['medence'], // Medence állapot
                        $data['szauna'],  // Szauna állapot
                        $data['megye_id'],
                        $data['belepesi_adatok'],
                    ];
        
                    if (!empty($data['kepek'])) {
                        $params[] = $data['kepek'];
                    }
        
                    $params[] = $id;
                    $params[] = $_SESSION['id'];
        
                    $eredmeny = adatokValtoztatasa($muvelet, $params);
        
                    if ($eredmeny === "Sikeres művelet!") {
                        echo json_encode(["success" => true, "message" => "Sikeres módosítás!"]);
                    } else {
                        http_response_code(400);
                        echo json_encode(["success" => false, "message" => $eredmeny]);
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