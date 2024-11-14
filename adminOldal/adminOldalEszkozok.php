<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="adminOldal.css">
    <title>Admin főoldal</title>
</head>
<body>
    <?php
        include "./sql_fuggvenyek/sqlGET.php";
    ?>

    
    <div class="pos-f-t">
        <div class="navbar-right">
            <div class="collapse" id="navbarToggleExternalContent">
                <div class="bg-dark p-4">
                    <h4 class="text-white">Collapsed content</h4>
                    <span class="text-muted">Toggleable via the navbar brand.</span>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-dark bg-dark">
            <div id="oldalLinkek">
                <a href=#>Felhasználók</a>
                <a href=#>Lakások</a>
                <p id="jelenlegiOldal">Eszközök</p>
            </div>


            <!--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>-->


            <h2 id="cim">főoldal</h2>
            <!--átalakítás backendre-->
            <div class="navbar-right" id="profilAdatok">


                <?php
                    function profilBetoltese(){
                        $muvelet = "";
                        $felhasznaloBetoltese = adatokLekerese($muvelet);
                    }
                ?>


                <h5 id="nev">név(XY)</h5>
                <img id="profilKep" src="./warhammer-40.jpg" alt="profilkép">
            </div>
        </nav>
    </div>

    <script src="adminOldal.js"></script>

</body>
</html>
