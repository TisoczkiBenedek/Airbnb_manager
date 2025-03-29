<?php
session_start();
include "./sql_fuggvenyek.php";
include "./profilBetoltes.php";

// Frissítési függvény
function eszkozFrissites($id, $mennyiseg) {
    $sql = "UPDATE `eszkoz` SET `keszletenDB`=$mennyiseg WHERE id=$id";
    return adatokValtoztatasa($sql) === 'Sikeres művelet!';
}

// Törlési függvény
function eszkozTorles($id) {
    $sql = "DELETE FROM `eszkoz` WHERE id=$id";
    return adatokValtoztatasa($sql) === 'Sikeres művelet!';
}

// Űrlap feldolgozás
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $atiranyitas = "http://".$_SERVER['HTTP_HOST'].str_replace('index.php', 'eszkozok.php', $_SERVER['SCRIPT_NAME']);
    
    // Új eszköz hozzáadása
    if (isset($_POST["uj_eszkoz"])) {
        if (!empty($_POST['nev']) && !empty($_POST['kiszereles']) && isset($_POST['mennyiseg'])) {
            $nev = $_POST['nev'];
            $kiszereles = $_POST['kiszereles'];
            $mennyiseg = (int)$_POST['mennyiseg'];

            if ($mennyiseg >= 0) {
                $sql = "INSERT INTO `eszkoz`(`nev`, `kiszereles`, `keszletenDB`) VALUES ('$nev', '$kiszereles', $mennyiseg)";
                $eredmeny = adatokValtoztatasa($sql);
                
                if ($eredmeny === 'Sikeres művelet!') {
                    echo "<script>alert('Sikeres hozzáadás!'); window.location.href = '$atiranyitas';</script>";
                } else {
                    echo "<script>alert('Hiba történt: $eredmeny'); window.location.href = '$atiranyitas';</script>";
                }
                exit;
            } else {
                echo "<script>alert('A mennyiség nem lehet negatív!'); window.location.href = '$atiranyitas';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Minden mezőt ki kell tölteni!'); window.location.href = '$atiranyitas';</script>";
            exit;
        }
    }
    
    // Igénylés teljesítése
    if (isset($_POST["teljesit"])) {
        $igenyles_id = (int)$_POST['igenyles_id'];
        if ($igenyles_id > 0) {
            $sql = "UPDATE `eszkozszukseglet` SET `teljesitve`=1 WHERE `id`=$igenyles_id";
            $eredmeny = adatokValtoztatasa($sql);
            
            if ($eredmeny === 'Sikeres művelet!') {
                echo "<script>alert('Igénylés teljesítve!'); window.location.href = '$atiranyitas';</script>";
            } else {
                echo "<script>alert('Hiba történt: $eredmeny'); window.location.href = '$atiranyitas';</script>";
            }
            exit;
        }
    }
    
    // Eszközök tömeges frissítése
    if (isset($_POST["frissites"])) {
        $sikeres_frissitesek = 0;
        foreach ($_POST['mennyiseg'] as $id => $mennyiseg) {
            $id = (int)$id;
            $mennyiseg = (int)$mennyiseg;
            if ($mennyiseg >= 0 && $id > 0) {
                if (eszkozFrissites($id, $mennyiseg)) {
                    $sikeres_frissitesek++;
                }
            }
        }
        echo "<script>alert('$sikeres_frissitesek eszköz sikeresen frissítve!'); window.location.href = '$atiranyitas';</script>";
        exit;
    }
    
    // Eszközök törlése
    if (isset($_POST["torles"])) {
        if (!empty($_POST['torlendo_eszkozok'])) {
            $sikeres_torlesek = 0;
            foreach ($_POST['torlendo_eszkozok'] as $id) {
                $id = (int)$id;
                if ($id > 0 && eszkozTorles($id)) {
                    $sikeres_torlesek++;
                }
            }
            echo "<script>alert('$sikeres_torlesek eszköz sikeresen törölve!'); window.location.href = '$atiranyitas';</script>";
        } else {
            echo "<script>alert('Nincs kiválasztott eszköz a törléshez!'); window.location.href = '$atiranyitas';</script>";
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/eszkozok.css">
    <title>Eszközkezelés</title>
</head>
<body>
    <!-- Navigáció -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
          <ul class="navbar-nav me-auto">
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  </a>
                  <ul class="dropdown-menu dropdown-menu-dark bg-dark" aria-labelledby="menu">
                      <li><a class="dropdown-item" href="../html/felhasznalok.html">Felhasználók</a></li>
                      <li><a class="dropdown-item" href="../html/lakasok.html">Lakások</a></li>
                      <li><a class="dropdown-item" href="../php/eszkozok.php">Eszközök</a></li>
                      <li><a class="dropdown-item" onclick="kijelentkezes()">Kijelentkezés</a></li>
                  </ul>
              </li>
          </ul>
        <h2 class="text-white mb-0 mx-3">Eszközkezelés</h2>
        <div class="navbar-right" id="profil_adatok">
          <span class="text-white" id="profil_nev" style="margin-right: 10px;"></span>
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" style="color: white;">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
          </svg>
        </div>
      </div>
    </nav>

    <div class="container mt-5 pt-4">
        <!-- Eszközök -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Eszközök kezelése</h3>
                <div class="d-flex align-items-center">
                <div class="input-group search-container me-2">
                    <input type="text" class="form-control form-control-sm" id="nevSzuro" placeholder="Keresés">
                    <button class="btn btn-light btn-sm" type="button" id="szuresGomb">
                        <i class="bi bi-search"></i>
                    </button>
                    <button class="btn btn-outline-light btn-sm" type="button" id="szuresReset">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ujEszkozModal">
                    <i class="bi bi-plus-circle"></i> Új eszköz
                </button>
            </div>
            <div class="card-body">
                <form method="post" action="eszkozok.php">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="checkbox-cell"><input type="checkbox" id="osszes_kivalasztasa"></th>
                                    <th>Név</th>
                                    <th>Kiszerelés</th>
                                    <th>Készleten</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $szuroNev = $_GET['nev'] ?? '';

                                $muvelet = "SELECT * FROM eszkoz WHERE 1"; 
                                $params = [];
                                
                                if(!empty($szuroNev)){
                                    $muvelet .= " nev LIKE ?";
                                    $params[] = "%szuroNev%";
                                }

                                $muvelet .= " ORDER BY nev";
                                $eszkozok = adatokLekerese($muvelet, $params);
                                
                                if (is_array($eszkozok)) {
                                    foreach ($eszkozok as $eszkoz) {
                                        echo "<tr>";
                                        echo "<td class='checkbox-cell'><input type='checkbox' name='torlendo_eszkozok[]' value='".$eszkoz['id']."'></td>";
                                        echo "<td>".htmlspecialchars($eszkoz['nev'])."</td>";
                                        echo "<td>".htmlspecialchars($eszkoz['kiszereles'])."</td>";
                                        echo "<td>
                                                <input type='number' class='form-control form-control-sm' 
                                                       name='mennyiseg[".$eszkoz['id']."]' 
                                                       value='".htmlspecialchars($eszkoz['keszletenDB'])."'
                                                       min='0'>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>$eszkozok</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="submit" name="torles" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Kijelöltek törlése
                        </button>
                        <button type="submit" name="frissites" class="btn btn-primary">
                            <i class="bi bi-save"></i> Eszköz frissítése
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Igénylések -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Jelenlegi eszköz igénylések</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Eszköz</th>
                                <th>Mennyiség</th>
                                <th>Művelet</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $muvelet = "
                            SELECT eszkozszukseglet.id,  eszkoz.nev AS eszkoz_nev, eszkozszukseglet.igenyeltDarab FROM `eszkozszukseglet` INNER JOIN eszkoz ON eszkoz.id = eszkozszukseglet.eszkozId WHERE eszkozszukseglet.teljesitve = 0;
                            ";
                            $igenylesek = adatokLekerese($muvelet);
                            
                            if (is_array($igenylesek)) {
                                foreach ($igenylesek as $igenyles) {
                                    echo "<tr>";
                                    echo "<td>".htmlspecialchars($igenyles['eszkoz_nev'])."</td>";
                                    //echo "<td>".htmlspecialchars($igenyles['Vezeteknev'])." ".htmlspecialchars($igenyles['Keresztnev'])."</td>";
                                    echo "<td>".htmlspecialchars($igenyles['igenyeltDarab'])."</td>";
                                    echo "<td>
                                            <form method='post' action='eszkozok.php' class='d-inline'>
                                                <input type='hidden' name='igenyles_id' value='".$igenyles['id']."'>
                                                <button type='submit' name='teljesit' class='btn btn-sm btn-success'>
                                                    <i class='bi bi-check-circle'></i> Teljesítve
                                                </button>
                                            </form>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>$igenylesek</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Új eszköz modal -->
        <div class="modal fade" id="ujEszkozModal" tabindex="-1" aria-labelledby="ujEszkozModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="ujEszkozModalLabel">Új eszköz hozzáadása</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Bezár"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="eszkozok.php">
                            <div class="mb-3">
                                <label for="nev" class="form-label">Eszköz neve:</label>
                                <input type="text" class="form-control" id="nev" name="nev" required>
                            </div>
                            <div class="mb-3">
                                <label for="kiszereles" class="form-label">Kiszerelés:</label>
                                <input type="text" class="form-control" id="kiszereles" name="kiszereles" required>
                            </div>
                            <div class="mb-3">
                                <label for="mennyiseg" class="form-label">Készleten:</label>
                                <input type="number" class="form-control" id="mennyiseg" name="mennyiseg" required min="0" value="0">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" name="uj_eszkoz">
                                    <i class="bi bi-save"></i> Mentés
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/eszkozok.js"></script>
    <script src="../js/kijelentkezes.js"></script>
</body>
</html>