<?php
//Hier bevindt zich de belangrijste core van onze website: Sessies, de connectie met de database, en functies
include 'session.php';
include 'connection.php';
include 'functions.php';
$vergelijkID = $_GET['vergelijk']
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="categorie-style.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <title></title>

    </head>
    <body>

        <!-- Hier wordt de navbar opgevraagd -->
        <?php
        include 'navbar.php';

        $aantalArtikelen = count($vergelijkID);
        if ($aantalArtikelen > 5) {
            if ($aantalArtikelen == 6) {
                $marginLeft = "200px";
            } else {
                $marginLeft = "0px";
            }
        } else {
            $marginLeft = "";
        }
        ?>       
        <style>
            .container {
                margin-left: <?php print($marginLeft); ?>
            }
        </style>
        <div class="container">

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <?php foreach ($vergelijkID as $vergelijking => $artikelID) { ?>                              
                            <?php
                            $query = $conn->query("SELECT * FROM stockitems SI LEFT JOIN colors C on SI.ColorID = C.colorID WHERE stockItemID = " . $artikelID);
                            while ($row = $query->fetch()) {
                                $artikelNaam = $row["StockItemName"]; //De naam van het artikel
                            }
                            ?>
                            <th scope="col"><?php print($artikelNaam) ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <!-- Foto -->
                        <th scope="row">Foto</th>
                        <?php foreach ($vergelijkID as $vergelijking => $artikelID) { ?>                              
                            <?php
                            $query = $conn->query("SELECT * FROM stockitems SI LEFT JOIN colors C on SI.ColorID = C.colorID WHERE stockItemID = " . $artikelID);
                            while ($row = $query->fetch()) {
                                $artikelID = $row["StockItemID"]; //Het ID van het artikel
                                ?>
                                <td><img src="artikelFoto/foto/<?php print($artikelID); ?>.jpg"  class="artikelImg"></td>
                                    <?php
                                }
                            }
                            ?>
                    </tr>
                    <tr>
                        <!-- De kleur -->
                        <th scope="row">Kleur</th>
                        <?php foreach ($vergelijkID as $vergelijking => $artikelID) { ?>                              
                            <?php
                            $query = $conn->query("SELECT * FROM stockitems SI LEFT JOIN colors C on SI.ColorID = C.colorID WHERE stockItemID = " . $artikelID);
                            while ($row = $query->fetch()) {
                                $artikelKleur = $row["ColorName"]; //De kleur van het artikel (is soms NULL)
                                ?>
                                <td><?php
                                    if (isset($artikelKleur)) {
                                        print($artikelKleur);
                                    } else {
                                        print("<i>Geen kleur gevonden</i>");
                                    }
                                    ?> </td>
                                <?php
                            }
                        }
                        ?>






                    </tr>
                    <tr>
                        <!-- Extra informatie -->
                        <th scope="row">Extra informatie</th>
                        <?php foreach ($vergelijkID as $vergelijking => $artikelID) { ?>                              
                            <?php
                            $query = $conn->query("SELECT * FROM stockitems SI LEFT JOIN colors C on SI.ColorID = C.colorID WHERE stockItemID = " . $artikelID);
                            while ($row = $query->fetch()) {
                                $slogan = $row["MarketingComments"]; //Extra informatie om mee te verkopen (is soms NULL)
                                ?>
                                <td><?php
                                    if ($slogan != '') {
                                        print($slogan);
                                    } else {
                                        print("<i>Geen extra informatie beschikbaar</i>");
                                    }
                                    ?> </td>
                                <?php
                            }
                        }
                        ?>






                    </tr>
                </tbody>
            </table>

        </div>    
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>
