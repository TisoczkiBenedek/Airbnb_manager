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

// Az action paraméter lekérése (pl. lekeres, getLakas, modositas, getNaptar)
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
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    try {
                        // ... (korábbi kód változatlan)
            
                        // 5. NAPTÁR FRISSÍTÉS KEZELÉSE
                        $naptarUzenet = null;
                        $naptarValtozas = false;
                        
                        if (!empty($_FILES['naptarFeltoltes']['tmp_name'])) {
                            $naptarValtozas = true;
                            $icsContent = file_get_contents($_FILES['naptarFeltoltes']['tmp_name']);
                            
                            // Ellenőrizzük, hogy van-e már naptár ehhez a lakáshoz
                            $letezoNaptar = adatokLekerese("SELECT id, file_content FROM naptarak WHERE lakas_id = ?", [$id]);
                            
                            // Ha nincs változás a tartalomban
                            if (!empty($letezoNaptar) && $letezoNaptar[0]['file_content'] === $icsContent) {
                                $naptarUzenet = "A naptárfájl változatlan maradt.";
                            } else {
                                if (!empty($letezoNaptar)) {
                                    $muvelet = "UPDATE naptarak SET file_name = ?, file_content = ? WHERE lakas_id = ?";
                                    $params = [$_FILES['naptarFeltoltes']['name'], $icsContent, $id];
                                } else {
                                    $muvelet = "INSERT INTO naptarak (file_name, file_content, lakas_id, felhasznalo_id) VALUES (?, ?, ?, ?)";
                                    $params = [$_FILES['naptarFeltoltes']['name'], $icsContent, $id, $_SESSION['id']];
                                }
                                
                                $naptarResult = adatokValtoztatasa($muvelet, $params);
                                $naptarUzenet = ($naptarResult === "Sikeres művelet!") 
                                    ? "Naptár sikeresen frissítve!" 
                                    : "Hiba történt a naptár frissítésekor!";
                            }
                        }
            
                        // 6. SQL FRISSÍTÉS (korábbi kód változatlan)
            
                        if ($eredmeny === "Sikeres művelet!") {
                            $message = "Lakás adatai sikeresen frissültek!";
                            
                            // Egyéni üzenetek összeállítása
                            if ($naptarValtozas) {
                                $message .= " " . $naptarUzenet;
                            } else if (empty($_FILES['kepFeltoltes']['name']) && empty($_FILES['naptarFeltoltes']['name'])) {
                                $message = "Nincsenek módosítandó adatok, vagy minden adat változatlan maradt.";
                            }
                            
                            echo json_encode(["success" => true, "message" => $message]);
                        } else {
                            http_response_code(400);
                            echo json_encode(["success" => false, "message" => $eredmeny]);
                        }
                    } catch (Exception $e) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    }
                }
                break;

    }
} catch (Exception $e) {
    // Szerverhiba esetén hibaüzenet
    http_response_code(500);
    echo json_encode(["valasz" => "Szerverhiba: " . $e->getMessage()]);
}