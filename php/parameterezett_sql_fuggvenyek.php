<?php
function adatokLekerese($query, $params = []) {
    // Kapcsolat létrehozása
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');
    
    // Kapcsolat létrejött-e
    if ($db->connect_errno == 0) {
        // SQL művelet előkészítése
        $stmt = $db->prepare($query);
        if ($stmt === false) {
            return ["error" => "Hiba az SQL előkészítésekor: " . $db->error];
        }

        // Paraméterek kötése, ha vannak
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // Minden paraméter string típusú
            $stmt->bind_param($types, ...$params);
        }

        // SQL végrehajtása
        $stmt->execute();

        // Eredmény lekérése
        $result = $stmt->get_result();
        if ($result === false) {
            return ["error" => "Hiba az SQL végrehajtásakor: " . $stmt->error];
        }

        // Adatok lekérése
        $adatok = $result->fetch_all(MYSQLI_ASSOC);
        return $adatok;
    } else {
        return ["error" => "Hiba az adatbázis kapcsolatban: " . $db->connect_error];
    }
}

function adatokValtoztatasa($query, $params = []) {
    // Kapcsolat létrehozása
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');
    
    // Kapcsolat létrejött-e
    if ($db->connect_errno == 0) {
        // SQL művelet előkészítése
        $stmt = $db->prepare($query);
        if ($stmt === false) {
            return "Hiba az SQL előkészítésekor: " . $db->error;
        }

        // Paraméterek kötése, ha vannak
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // Minden paraméter string típusú
            $stmt->bind_param($types, ...$params);
        }

        // SQL végrehajtása
        $stmt->execute();

        // Eredmény ellenőrzése
        if ($stmt->affected_rows > 0) {
            return "Sikeres művelet!";
        } else {
            return "Sikertelen művelet: " . $stmt->error;
        }
    } else {
        return "Hiba az adatbázis kapcsolatban: " . $db->connect_error;
    }
}