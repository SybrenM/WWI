<?php
//Hier bevindt zich de belangrijste core van onze website: Sessies, de connectie met de database, en functies
include 'session.php';
include 'connection.php';
include 'functions.php';

//Hier pakt de pagina 2 variabelen uit de topbar
$artikelID = filter_input(INPUT_GET, "artikelid", FILTER_SANITIZE_STRING); //Het artikelID van het weer te geven artikel
$maatSelected = filter_input(INPUT_GET, "maatselected", FILTER_SANITIZE_STRING); //Hier wordt aangegeven of de maat van een bepaald artikel al geselecteerd is. Dit is standaart FALSE, maar wordt bij het kiezen van een maat TRUE.
//Standaar is er geen probleem met de maten van kleding oid. Dus wordt dat hier even standaart gedefined.
$sizeProblem = FALSE;
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Meta tags die belangrijk zijn -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="artikel-style.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

        <style>
            .placeholder{
                max-width: 100%;
            }
            .description, .prijs{
                margin-top: 100px;
            }
        </style>
        <title>Hello, world!</title> 
    </head>
    <?php
//Hier wordt één specifiek artikel opgezocht met het artikelID die meekomt uit de link
    $query = $conn->query("SELECT * FROM stockitems SI JOIN suppliers S on SI.supplierID = S.supplierID WHERE SI.stockitemid = " . $artikelID);
    $count = $query->rowCount();
    $artikelNaamTweedeKeer = '';
    while ($row = $query->fetch()) {
        $artikelNaam = $row["StockItemName"]; //De naam van het artikel
        $artikelID = $row["StockItemID"]; //Het ID van het artikel
        $artikelPrijs = $row["RecommendedRetailPrice"]; //De prijs van het artikel
        $artikelMaat = $row["Size"]; //De eventuele maat van het artikelen (is soms NULL)
        $slogan = $row["MarketingComments"]; //De eventuele slogan van het artikel (is soms NULL)
        $derdePartij = $row["SupplierName"]; //De leverancier van het artikel
        //De functie 'like_match' die hier onder gebruikt wordt staat in functions.php
        $Small = like_match('%S', $artikelMaat); //Hier wordt gekeken of de maat een S, XS, XXS oid is
        $Medium = like_match('M', $artikelMaat); //Hier wordt gekeken of de maat een M is
        $Large = like_match('%L', $artikelMaat); //Hier wordt gekeken of de maat een L, XL, XXL oid is
        $LengteEen = like_match('%.%m', $artikelMaat); //Hier wordt gekeken of de maat iets met een niet-afgerond getal is, zoals bijv 1,5m; of 22.6m of 55.9m
        $LengteTwee = like_match('%0m', $artikelMaat); //Hier wordt gekeken of de maat iets met een tiental is, zoals bijv 10m, 20m of 50m
        $LengteDrie = like_match('%x%m', $artikelMaat); //Hier wordt gekeken of de maat iets heeft in m2. dus bijv 224mm x 550mm
        //Hier wordt gekeken of er tussen het artikel iets zit met een lengte
        if ($LengteEen OR $LengteTwee OR $LengteDrie) {
            $artikelDing = str_replace($artikelMaat, '', $artikelNaam); //Artikelding is de naam van het artikel zonder eventuele maat er achter
            $sizeProblem = TRUE; //Omdat dit dus een geval ik met een lastige lengte-weergave, wordt dit true
        }
        //Hier wordt gekeken of het artikel iets heeft met een maatje S, M of L.
        if ($Small OR $Medium OR $Large) {
            $artikelDing = str_replace(") " . $artikelMaat, '', $artikelNaam); //Artikelding is de naam van het artikel zonder eventuele lengte er achter (Het haaakje er voor is om te kijken of de maat aan het einde wordt weergegeven, zodat niet ergens anders in het woord dingen worden aangepast
            $artikelDing = $artikelDing . ")"; //Hier wordt het haakje weer teruggezet, omdat deze alleen werd weggehaald om de positie te ontdekken
            $sizeProblem = TRUE; //Omdat dit dus een geval ik met een lastige maat-weergave, wordt dit true
        }
        ?>
        <body>
            <!-- Hier wordt de navbar opgevraagd -->
            <?php include 'navbar.php'; ?>
            <!-- Hier komt de pagina zelf -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-6" >
                        <div class="w3-content w3-section">
                            <?php $fotoID = rand(1, 17); ?>
                            <img src="artikelFoto/<?php print($fotoID); ?>.jpg"  class="mySlides w3-animate-fading" width="500px" height="500px">
                            <?php $fotoID = rand(1, 17); ?>
                            <img src="artikelFoto/<?php print($fotoID); ?>.jpg"  class="mySlides w3-animate-fading" width="500px" height="500px">
                            <?php $fotoID = rand(1, 17); ?>
                            <img src="artikelFoto/<?php print($fotoID); ?>.jpg"  class="mySlides w3-animate-fading" width="500px" height="500px">

                        </div>

                        <script>
                            var myIndex = 0;
                            carousel();

                            function carousel() {
                                var i;
                                var x = document.getElementsByClassName("mySlides");
                                for (i = 0; i < x.length; i++) {
                                    x[i].style.display = "none";
                                }
                                myIndex++;
                                if (myIndex > x.length) {
                                    myIndex = 1
                                }
                                x[myIndex - 1].style.display = "block";
                                setTimeout(carousel, 8000);
                            }
                        </script>
                    </div>
                    <div class="col-lg-6">
                        <strong>Product: </strong><?php
                        //Als het bekende maatprobleem zich afspeelt, en daarbij de maat nog niet geselecteerd is, dan wordt het artikel weergegeven zonder maat. (Er is dus nog geen maat gekozen.)
                        if ($maatSelected == 'FALSE' AND $sizeProblem) {
                            print($artikelDing); //Artikelnaam wordt weergegeven zonder maat er achter. (dus niet zoals dat standaart in de database staat.)
                        } ELSE {
                            print($artikelNaam); //Artikelnaam wordt weergegeven met maat er achter. (Dus zoals dat standaart in de database staat.)
                        }

                        //Hier wordt gekeken of het een artikel is met een maatprobleem, en of de maat in dat geval ook al geselecteerd is
                        if ($maatSelected == 'TRUE' OR ! $sizeProblem) {
                            ?><div class = "prijs"><strong>Prijs: </strong><?php
                            print("€" . $artikelPrijs);
                        } else {
                            ?><div class = "prijs"><strong>Prijs:</strong><?php
                            }
                            ?> </div>
                            <!--Hier wordt gekeken of het maatprobleem aan de orde is-->
                            <?php if ($sizeProblem) { ?> 
                                <div class="dropdown"> 
                                    <button class="btn kleur dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php
                                        //Als de maat nog niet geselecteerd is, komt er te staan: 'Selecteer uw maat'
                                        if ($maatSelected == "FALSE") {
                                            print("Selecteer uw maat:");
                                        } else {
                                            //als de maat al wel geselecteerd is, komt er de maat te staan.
                                            print($artikelMaat);
                                        }
                                        ?>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <?php
                                        //Hier selecteert deze query de stockitems met een vergelijkbare naam (Zonder maat!)
                                        $row = $conn->query("SELECT * FROM stockitems WHERE stockItemName LIKE '%" . $artikelDing . "%'");
                                        while ($artikel = $row->fetch()) {
                                            $sizeNaam = $artikel["Size"];
                                            //Hier selecteerd deze query de stockitems op maat. Je krijgt dus alle maten te zien van één artikel
                                            $row2 = $conn->query("SELECT * FROM stockitems WHERE stockItemName LIKE '%" . $artikelDing . "%' AND Size = '" . $sizeNaam . "'");
                                            while ($artikel2 = $row2->fetch()) {
                                                $artikelIDLink = $artikel2["StockItemID"];
                                                ?>
                                                <a class = "dropdown-item" href = "artikel.php?artikelid=<?php print($artikelIDLink . "&maatselected=TRUE"); ?>"><?php print($sizeNaam);
                                                ?></a>
                                                <?php
                                            }
                                        }
                                        ?> 
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="description"><strong>Extra informatie:</strong> 
                                <?php
                                if ($slogan) {
                                    print("<i>".$slogan."</i>");
                                } else {
                                    print("<i>Dit artikel bevat geen extra, relevante, informatie.</i>");
                                }
                                ?> </div>
                        </div>
                    </div><strong>
                    <?php
                } print("&#9400 ".$derdePartij);
                ?> </strong><div class="row"> <?php
                //Hier wordt gekeken of het maatprobleem aan de orde is en of in dat geval de maat al geselecteerd is
                if ($maatSelected == 'TRUE' OR ! $sizeProblem) {
                    if (empty($_SESSION['winkelwagen'])) {
                        $_SESSION['winkelwagen'] = array();
                        ?>
                            <div class="row">
                                <form class="winkelmandToevoeg" action="winkelwagen.php" method="post">
                                    <input class="form-control winkelmandToevoegenAantal" type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
                                    <input class="winkelmandToevoegenAantal" type="number" name="number" onkeypress="return event.charCode >= 48" min="1">
                                    <!-- Hier moet nog een maximum komen (Maximum moet variable van de voorraad zijn -->
                                    <input class="btn winkelmandToevoegen" type="submit" value="Aan winkelmand toevoegen">
                                </form>
                            </div>
                            <?php
                        }

                        foreach ($_SESSION['winkelwagen'] as $key => $value) {
                            if ($artikelID == $value) {
                                echo ' <br> <i>U heeft dit artikel al in uw winkelwagen staan</i>';
                                $exists = "";
                            }
                        }
                        if (!isset($exists) && !empty($_SESSION['winkelwagen'])) {
                            ?>

                            <form class="winkelmandToevoeg" action="winkelwagen.php" method="post">
                                <input class="form-control winkelmandToevoegenAantal" type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
                                <input class="form-control winkelmandToevoegenAantal" type="number" name="number" onkeypress="return event.charCode >= 48" min="1"> <!-- Hier moet nog een maximum komen (Maximum moet variable van de voorraad zijn -->
                                <input class="btn winkelmandToevoegen" type="submit" value="Aan winkelmand toevoegen">
                            </form>
                            <?php
                        }
                    } else {
                        print("<BR><BR>Selecteer eerst een maat");
                    }
                    ?>
                </div>
                <!-- Optional JavaScript -->
                <!-- jQuery first, then Popper.js, then Bootstrap JS -->
                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
                </body>
                </html>  