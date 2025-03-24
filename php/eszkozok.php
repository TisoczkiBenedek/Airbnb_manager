<?php
session_start();
include "./sql_fuggvenyek.php";
include "./profilBetoltes.php";

// Ellenőrizzük, hogy már definiálva van-e a függvény
if (!function_exists('getDBConnection')) {
    function getDBConnection() {
        $db = new mysqli('localhost', 'root', '', 'vizsgaremek_takaritas');
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        return $db;
    }
}

// Form feldolgozás
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $redirect_url = "http://".$_SERVER['HTTP_HOST'].str_replace('index.php', 'eszkozok.php', $_SERVER['SCRIPT_NAME']);
    
    if (isset($_POST["feltoltes"])) {
        if (!empty($_POST['nev']) && !empty($_POST['kiszereles']) && isset($_POST['keszletenDB'])) {
            $nev = $_POST['nev'];
            $kiszereles = $_POST['kiszereles'];
            $keszletenDB = (int)$_POST['keszletenDB'];

            if ($keszletenDB >= 0) {
                $db = getDBConnection();
                $query = $db->prepare("INSERT INTO `eszkoz`(`nev`, `kiszereles`, `keszletenDB`) VALUES (?, ?, ?)");
                $query->bind_param("ssi", $nev, $kiszereles, $keszletenDB);
                $result = $query->execute() && $query->affected_rows > 0;
                $query->close();
                $db->close();
                
                echo "<script>alert('".($result ? 'Sikeres adatfeltöltés!' : 'Hiba történt!')."'); window.location.href = '$redirect_url';</script>";
                exit;
            } else {
                echo "<script>alert('A készlet szám pozitív kell legyen!'); window.location.href = '$redirect_url';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Töltsd ki az összes mezőt!'); window.location.href = '$redirect_url';</script>";
            exit;
        }
    }
    // Hasonlóan kezeld a frissites és torles eseteket
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/adminOldalEszkozok.js" defer></script>
    <link rel="stylesheet" href="../css/eszkozok.css">
    <title>Eszközkezelés</title>
</head>
<body>

<!--
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/adminOldalEszkozok.js" defer></script>
    <link rel="stylesheet" href="../css/eszkozok.css">
    <title>Eszközkezelés</title>
</head>
<body>
-->
    <div class="pos-f-t">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
          <ul class="navbar-nav me-auto">
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  </a>
                  <ul class="dropdown-menu dropdown-menu-dark bg-dark" aria-labelledby="navbarDropdown">
                      <li><a class="dropdown-item" href="../html/felhasznalok.html">Felhasználók</a></li>
                      <li><a class="dropdown-item" href="../html/lakasok.html">Lakások</a></li>
                      <li><a class="dropdown-item" href="../php/eszkozok.php">Eszközök</a></li>
                      <li><a class="dropdown-item" onclick="kijelentkezes()">Kijelentkezés</a></li>
                  </ul>
              </li>
          </ul>
        <h2 id="cim" class="text-white mb-0 mx-3">Eszközök</h2>
        <div class="navbar-right" id="profilAdatok">
          <span class="text-white" id="profilNev" style="margin-right: 10px;"></span>
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" style="color: white;">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
          </svg>
        </div>
      </div>
    </nav>
    </div>

    <div class="container mt-5 pt-4" id="eszkozTarolo">
        <!-- Fő űrlap -->
        <form method="post" action="eszkozok.php">
            <input type='hidden' name='frissites' value='1'>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eszkozModal">Eszköz felvitele</button>
            <button type="submit" class="btn btn-primary" name='frissites'>Raktár frissítése</button>
            <button type="submit" class="btn btn-danger" name='torles'>Kijelöltek törlése</button>
            <hr>
            <?php
                include "./eszkozBetoltes.php";
            ?>
        </form>
        
        <!-- Bootstrap Modal az új eszközhöz -->
        <div class="modal fade" id="eszkozModal" tabindex="-1" aria-labelledby="eszkozModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eszkozModalLabel">Új eszköz</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="eszkozok.php">
                            <div class="mb-3">
                                <label for="nev" class="form-label">Név:</label>
                                <input type="text" class="form-control" id="nev" name="nev" required>
                            </div>
                            <div class="mb-3">
                                <label for="kiszereles" class="form-label">Kiszerelés:</label>
                                <input type="text" class="form-control" id="kiszereles" name="kiszereles" required>
                            </div>
                            <div class="mb-3">
                                <label for="keszletenDB" class="form-label">Darabszám:</label>
                                <input type="number" class="form-control" id="keszletenDB" name="keszletenDB" required min="0">
                            </div>
                            <button type="submit" class="btn btn-primary" name="feltoltes">Mentés</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>