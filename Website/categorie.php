<?php
include 'session.php';
include 'connection.php';
include 'functions.php';
$categorie = filter_input(INPUT_GET, "categorie", FILTER_SANITIZE_STRING);
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
        <?php include 'navbar.php'; ?>


        <div class="container">
            <!-- Hier komt de lijst met artikelen die een bepaald categorie hebben -->
            <?php
            $query = $conn->query("SELECT * FROM stockitems SI JOIN stockitemstockgroups SISG on SI.StockItemID = SISG.StockItemID WHERE SISG.StockgroupID = " . $categorie);
            $artikelNaamTweedeKeer = '';
            while ($row = $query->fetch()) {
                $artikelNaam = $row["StockItemName"];
                $artikelID = $row["StockItemID"];
                $artikelPrijs = $row["RecommendedRetailPrice"];
                $artikelMaat = $row["Size"];
                $Small = like_match('%S', $artikelMaat);
                $Medium = like_match('M', $artikelMaat);
                $Large = like_match('%L', $artikelMaat);
                $LengteEen = like_match('%.%m', $artikelMaat);
                $LengteTwee = like_match('%0m', $artikelMaat);
                $LengteDrie = like_match('%x%m', $artikelMaat);
                //Hier wordt gekeken of er tussen de artikelen iets zit met een maatje S, M of L. Hiervan wordt er maar één weergegeven tussen de resultaten. De maat kan dan pas op de artikelpagina zelf worden gekozen.
                if ($LengteEen OR $LengteTwee OR $LengteDrie) {
                    $artikelNaam = str_replace($artikelMaat, '', $artikelNaam);
                    if ($artikelNaam != $artikelNaamTweedeKeer) {
                        $artikelNaamTweedeKeer = $artikelNaam;
                    } else {
                        goto a;
                    }
                }
                if ($Small OR $Medium OR $Large) {
                    $artikelNaam = str_replace(") " . $artikelMaat, '', $artikelNaam);
                    $artikelNaam = $artikelNaam . ")";
                    if ($artikelNaam != $artikelNaamTweedeKeer) {
                        $artikelNaamTweedeKeer = $artikelNaam;
                    } else {
                        goto a;
                    }
                }

                if (isset($artikelID)) {
                    ?>         
                    <!-- Hier komt een rij voor één artikel met, van links naar rechts: foto, naam, prijs -->
                    <a href="artikel.php?artikelid=<?php print($artikelID . "&maatselected=FALSE"); ?>" class="artikelTekst">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <?php $fotoID = rand(1, 17); ?>
                                <img src="artikelFoto/<?php print($fotoID); ?>.jpg"  class="artikelImg">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <h5><?php print($artikelNaam); ?></h5>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <?php print("<h3 class='artikelPrijs'>€" . $artikelPrijs . "</h3>"); ?>
                            </div>
                            <hr style="width:80%">
                        </div>
                    </a>
                    <?php
                    a:
                } else {
                    print("Niks gevonden.");
                }
            }
            ?>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>
