<?php
session_start();
include "./sql_fuggvenyek.php";
include "./profilBetoltes.php";

// Database connection function
function getDBConnection() {
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    return $db;
}

// Add new tool function
function feltoltes($nev, $kiszereles, $keszletenDB) {
    $db = getDBConnection();
    $query = $db->prepare("INSERT INTO `eszkoz`(`nev`, `kiszereles`, `keszletenDB`) VALUES (?, ?, ?)");
    $query->bind_param("ssi", $nev, $kiszereles, $keszletenDB);

    $result = $query->execute() && $query->affected_rows > 0;
    
    $query->close();
    $db->close();
    
    return $result;
}

// Update tool function
function frissites($keszletenDB, $Id) {
    $db = getDBConnection();
    $query = $db->prepare("UPDATE `eszkoz` SET `keszletenDB`=? WHERE Id=?");
    $query->bind_param("ii", $keszletenDB, $Id);

    $result = $query->execute() && $query->affected_rows > 0;
    
    $query->close();
    $db->close();
    
    return $result;
}

// Delete tool function
function torles($Id) {
    $db = getDBConnection();
    $query = $db->prepare("DELETE FROM `eszkoz` WHERE Id=?");
    $query->bind_param("i", $Id);

    $result = $query->execute() && $query->affected_rows > 0;
    
    $query->close();
    $db->close();
    
    return $result;
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["feltoltes"])) {
        if (!empty($_POST['nev']) && !empty($_POST['kiszereles']) && isset($_POST['keszletenDB'])) {
            $nev = $_POST['nev'];
            $kiszereles = $_POST['kiszereles'];
            $keszletenDB = (int)$_POST['keszletenDB'];

            if ($keszletenDB >= 0) {
                $uzenet = feltoltes($nev, $kiszereles, $keszletenDB);
                if ($uzenet) {
                    echo "<script>alert('Sikeres adatfeltöltés!'); window.location.href = 'eszkozok.php';</script>";
                } else {
                    echo "<script>alert('Hiba történt az adatfeltöltés során!'); window.location.href = 'eszkozok.php';</script>";
                }
            } else {
                echo "<script>alert('Kérjük, győződj meg róla, hogy a készlet szám pozitív!'); window.location.href = 'eszkozok.php';</script>";
            }
        } else {
            echo "<script>alert('Kérjük, töltsd ki a kötelező mezőket!'); window.location.href = 'eszkozok.php';</script>";
        }
    }
    elseif (isset($_POST["frissites"])) {
        $adatokVannak = false;
        $uzenetek = [];
        
        foreach ($_POST as $kulcs => $ertek) {
            if (strpos($kulcs, 'darab_') === 0) {
                $adatokVannak = true;
                $Id = str_replace('darab_', '', $kulcs);
                $keszletenDB = (int)$ertek;
                if ($keszletenDB >= 0) {
                    $uzenet = frissites($keszletenDB, $Id);
                    if ($uzenet) {
                        $uzenetek[] = "Sikeres frissítés ID: $Id!";
                    } else {
                        $uzenetek[] = "Hiba történt a frissítés során ID: $Id!";
                    }
                } else {
                    $uzenetek[] = "A készlet szám nem lehet negatív ID: $Id!";
                }
            }
        }
    }
    elseif (isset($_POST["torles"])) {
        $adatokVannak = false;
        $uzenetek = [];
        
        foreach ($_POST as $kulcs => $ertek) {
            if (strpos($kulcs, 'torlesCheckbox_') === 0 && $ertek == 'on') {
                $adatokVannak = true;
                $Id = str_replace('torlesCheckbox_', '', $kulcs);
                $uzenet = torles($Id);
                if ($uzenet) {
                    $uzenetek[] = "Sikeres törlés ID: $Id!";
                } else {
                    $uzenetek[] = "Hiba történt a törlés során ID: $Id!";
                }
            }
        }

        if ($adatokVannak) {
            echo "<script>alert('" . implode("\\n", $uzenetek) . "'); window.location.href = 'eszkozok.php';</script>";
        } else {
            echo "<script>alert('Nincs adat a törléshez!'); window.location.href = 'eszkozok.php';</script>";
        }
    }
}
?>