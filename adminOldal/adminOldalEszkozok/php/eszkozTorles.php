<?php
function torles($Id) {
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $query = $db->prepare("DELETE FROM `eszkoz` WHERE Id=?");
    if ($query === false) {
        echo "<script>console.log('Prepare failed: " . $db->error . "');</script>";
        return false;
    }

    $bind = $query->bind_param("i", $Id);
    if ($bind === false) {
        echo "<script>console.log('Bind param failed: " . $query->error . "');</script>";
        return false;
    }

    $execute = $query->execute();
    if ($execute === false) {
        echo "<script>console.log('Execute failed: " . $query->error . "');</script>";
        return false;
    }

    if ($query->affected_rows > 0) {
        return true;
    } else {
        echo "<script>console.log('No rows affected');</script>";
        return false;
    }

    $query->close();
    $db->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["torles"])) {
    $adatokVannak = false;
    $uzenetek = [];
    
    foreach ($_POST as $kulcs => $ertek) {
        if (strpos($kulcs, 'torlesCheckbox_') === 0 && $ertek == 'on') {
            $adatokVannak = true;
            $Id = str_replace('torlesCheckbox_', '', $kulcs);
            $uzenet = torles($Id);
        }
    }

    if ($adatokVannak) {
        foreach ($uzenetek as $uzenet) {
            echo "<script>alert('$uzenet');</script>";
        }
    } else {
        echo "<script>alert('Nincs adat a törléshez!');</script>";
    }
}
?>
