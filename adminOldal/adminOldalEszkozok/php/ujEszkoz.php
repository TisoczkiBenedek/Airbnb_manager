<?php
function feltoltes($nev, $kiszereles, $keszletenDB) {
    $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $query = $db->prepare("INSERT INTO `eszkoz`(`nev`, `kiszereles`, `keszletenDB`) VALUES (?, ?, ?)");
    $query->bind_param("ssi", $nev, $kiszereles, $keszletenDB);

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["feltoltes"])) {
    if (!empty($_POST['nev']) && !empty($_POST['kiszereles']) && isset($_POST['keszletenDB'])) {
        $nev = $_POST['nev'];
        $kiszereles = $_POST['kiszereles'];
        $keszletenDB = (int)$_POST['keszletenDB'];

        if ($keszletenDB >= 0) {
            $uzenet = feltoltes($nev, $kiszereles, $keszletenDB);
            if ($uzenet) {
                echo "<script>alert('Sikeres adatfeltöltés!'); window.location.href = 'adminOldalEszkozok.php';</script>";
            } else {
                echo "<script>alert('Hiba történt az adatfeltöltés során!'); window.location.href = 'adminOldalEszkozok.php';</script>";
            }
        } else {
            echo "<script>alert('Kérjük, győződj meg róla, hogy a készlet szám pozitív!'); window.location.href = 'adminOldalEszkozok.php';</script>";
        }
    } else {
        echo "<script>alert('Kérjük, töltsd ki a kötelező mezőket!'); window.location.href = 'adminOldalEszkozok.php';</script>";
    }
}
?>
