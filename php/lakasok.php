<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    die(json_encode(["valasz" => "Nincs bejelentkezve."]));
}

include './parameterezett_sql_fuggvenyek.php';

$action = $_GET['action'] ?? null;

if ($action === 'getProfilNev') {
    // Profilnév lekérése
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
    switch ($action) {
        case 'lekeres':
            $muvelet = "SELECT * FROM lakas WHERE felhasznalo_id = ?";
            $eredmeny = adatokLekerese($muvelet, [$_SESSION['id']]);
            
            if (is_array($eredmeny)) {
                foreach ($eredmeny as &$lakas) {
                    $lakas['kepek'] = [$lakas['kepek'] ?: '../images/default.jpg'];
                }
                echo json_encode($eredmeny, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(["valasz" => "Nincsenek lakások"]);
            }
            break;

        case 'getLakas':
            $id = $_GET['id'];
            $muvelet = "SELECT * FROM lakas WHERE id = ? AND felhasznalo_id = ?";
            $eredmeny = adatokLekerese($muvelet, [$id, $_SESSION['id']]);
            
            if (is_array($eredmeny) && count($eredmeny) > 0) {
                $lakas = $eredmeny[0];
                $lakas['kepek'] = [$lakas['kepek'] ?: '../images/default.jpg'];
                echo json_encode($lakas, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(["valasz" => "Lakás nem található"]);
            }
            break;

        case 'modositas':
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
                        'medence' => isset($_POST['medence']) ? 1 : 0,
                        'szauna' => isset($_POST['szauna']) ? 1 : 0,
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
        
                    // 3. RÉGI KÉP TÖRLÉSE, HA LÉTEZIK
                    if ($regiKep && file_exists($regiKep)) {
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

        case 'getNaptar':
            $id = $_GET['id'];
            $muvelet = "SELECT file_content FROM naptarak WHERE lakas_id = ?";
            $eredmeny = adatokLekerese($muvelet, [$id]);

            if (is_array($eredmeny) && count($eredmeny) > 0) {
                echo json_encode(['file_content' => $eredmeny[0]['file_content']]);
            } else {
                echo json_encode(['file_content' => null]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(["valasz" => "Érvénytelen művelet"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["valasz" => "Szerverhiba: " . $e->getMessage()]);
}