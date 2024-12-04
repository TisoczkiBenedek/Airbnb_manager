<?php
function frissites($keszletenDB, $Id) {
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $query = $db->prepare("UPDATE `eszkoz` SET `keszletenDB`=? WHERE Id=?");
    $query->bind_param("ii", $keszletenDB, $Id);

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }

    $query->close();
    $db->close();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["frissites"])) {
        foreach ($_POST as $kulcs => $ertek) {
            if(strpos($kulcs, 'darab_') === 0) {
                $Id = str_replace('darab_', '', $kulcs);
                $keszletenDB = (int)$ertek;
                if ($keszletenDB >= 0) {
                    $uzenet = frissites($keszletenDB, $Id);
                } else {
                    echo "<script>alert('A készlet szám nem lehet negatív!');</script>";
                }
            }
        }
    }
}
?>
