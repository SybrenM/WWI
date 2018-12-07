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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            .placeholder{
                max-width: 100%;
            }
            .description, .prijs{
                margin-top: 100px;
            }
        </style>
        <title>Artikel-pagina</title> 
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
            $sizeProblem = TRUE; //Omdat dit dus een geval is met een lastige lengte-weergave, wordt dit true
        }

        //Hier wordt gekeken of het artikel iets heeft met een maatje S, M of L.
        if ($Small OR $Medium OR $Large) {
            $artikelDing = str_replace(") " . $artikelMaat, '', $artikelNaam); //Artikelding is de naam van het artikel zonder eventuele lengte er achter (Het haaakje er voor is om te kijken of de maat aan het einde wordt weergegeven, zodat niet ergens anders in het woord dingen worden aangepast
            $artikelDing = $artikelDing . ")"; //Hier wordt het haakje weer teruggezet, omdat deze alleen werd weggehaald om de positie te ontdekken
            $sizeProblem = TRUE; //Omdat dit dus een geval is met een lastige maat-weergave, wordt dit true
        }

        $stmt = $conn->query("SELECT QuantityOnHand FROM stockitemholdings WHERE StockItemID = " . $artikelID);
        while ($maxArtikel = $stmt->fetch()) {
            $maxCount = $maxArtikel["QuantityOnHand"];
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
                            <img src="artikelFoto/foto/<?php print($artikelID); ?>.jpg"  class="mySlides w3-animate-fading" width="500px" height="500px">
                            <img src="artikelFoto/foto/<?php print($artikelID + 1); ?>.jpg"  class="mySlides w3-animate-fading" width="500px" height="500px">
                            <img src="artikelFoto/foto/<?php print($artikelID - 1); ?>.jpg"  class="mySlides w3-animate-fading" width="500px" height="500px">

                        </div>

                        <script>
                            //Hier zit de code voor de slideshow
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
                                setTimeout(carousel, 8000); //Duur van foto's in ms
                            }

                        </script>
                    </div>
                    <div class="col-lg-6">
                        <strong>Product: </strong><?php
                        //Als het bekende maatprobleem zich afspeelt, en daarbij de maat nog niet geselecteerd is, dan wordt het artikel weergegeven zonder maat. (Er is dus nog geen maat gekozen.)
                        if ($maatSelected == 'FALSE' AND $sizeProblem) {
                            print($artikelDing); //Artikelnaam wordt weergegeven zonder maat er achter. (dus niet zoals dat standaart in de database staat.)
                        } else {
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
                                    print("<i>" . $slogan . "</i>");
                                } else {
                                    print("<i>Dit artikel bevat geen extra, relevante, informatie.</i>");
                                }
                                ?> 
                            </div>

                            <BR> <?php
                            if ($maxCount > 10000) {
                                $maxCount = $maxCount / 5;
                                echo "Het maximum aantal dat u van " . $artikelNaam . " kan bestellen is " . round($maxCount) . ".";
                            } elseif ($maxCount > 1000 && $maxCount < 10000) {
                                $maxCount = $maxCount / 7;
                                echo "Het maximum aantal dat u van " . $artikelNaam . " kan bestellen is " . round($maxCount) . ".";
                            } elseif ($maxCount < 100) {
                                $maxCount = $maxCount;
                                echo "Het maximum aantal dat u van " . $artikelNaam . " kan bestellen is " . round($maxCount) . ".";
                            } else {
                                $maxCount = $maxCount / 7;
                                echo "Het maximum aantal dat u van " . $artikelNaam . " kan bestellen is " . round($maxCount) . ".";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <?php
            } print($derdePartij);
            //Hier wordt gekeken of het maatprobleem aan de orde is en of in dat geval de maat al geselecteerd is
            if ($maatSelected == 'TRUE' OR ! $sizeProblem) {
                if (empty($_SESSION['winkelwagen'])) {
                    $_SESSION['winkelwagen'] = array();
                    ?>
                    <form action="winkelwagen.php" method="post">
                        <input type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
                        <input type="number" name="number" onkeypress="return event.charCode >= 48" min="1" max="<?php echo round($maxCount) ?>"> <!-- Hier moet nog een maximum komen (Maximum moet variable van de voorraad zijn -->
                        <input type="submit" value="Aan winkelmand toevoegen" class="btn btn-primary">
                    </form>

                    <?php
                }

                foreach ($_SESSION['winkelwagen'] as $key => $value) {
                    if ($artikelID == $value) {
                        echo ' <br> U heeft dit artikel al in uw winkelwagen staan';
                        $exists = "";
                    }
                }
                if (!isset($exists) && !empty($_SESSION['winkelwagen'])) {
                    ?>

                    <form action="winkelwagen.php" method="post">
                        <input type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
                        <input type="number" name="number" onkeypress="return event.charCode >= 48" min="1" max="<?php echo round($maxCount) ?>"> <!-- Hier moet nog een maximum komen (Maximum moet variable van de voorraad zijn -->
                        <input type="submit" value="Aan winkelmand toevoegen" class="btn btn-primary">
                    </form>
                    <?php
                }
            } else {
                print("<BR><BR>Selecteer eerst een maat");
            }
            ?>
            <BR>
            <!--Tom's verlanglijstje-->
            <?php
            //Voordat iets op het verlanglijstje kan, moet de maat worden geselecteerd. Hier wordt gekeken of de maatgeselecteerd is Als er voor het product geen maat is, dan is het geen issue. Als aan deze condities voldaan wordt, dan wordt een session geset met het specifieke product.
            if ($maatSelected == 'TRUE' OR ! $sizeProblem) {
                if (empty($_SESSION['verlanglijstje'])) {
                    $_SESSION['verlanglijstje'] = array();
                    ?>
                    <form action="verlanglijstje.php" method="post">
                        <input type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
                        <input type="hidden" name="number" value="1"> 

                        <input type="submit" value="Aan verlanglijstje toevoegen" class="btn btn-primary">
                    </form>

                    <?php
                }

                foreach ($_SESSION['verlanglijstje'] as $key => $value) {
                    if ($artikelID == $value) {
                        echo ' <br> U heeft dit artikel al op uw verlanglijstje staan';
                        $exists = "";
                    }
                }

                if (!isset($exists) && !empty($_SESSION['verlanglijstje'])) {
                    ?>
                    <div class="container">
                        <form action="verlanglijstje.php" method="post">
                            <input type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
                            <input type="hidden" name="number" value="1"> 
                            <input type="submit" value="Aan verlanglijstje toevoegen" class="btn btn-primary">
                        </form>
                    </div>

                    <?php
                }
            } else {
                //Als de maat nog niet is geselecteerd, dan wordt de klant hierop gewezen
                print("<BR><BR>Kan niet op verlanglijstje zetten. Selecteer eerst een maat ");
            }
            ?>

            <!--Tom's Code Review-->
            <?php
            //Hier wordt gecontroleerd of de $_SESSION['email'] is geset. als dit het geval is, dan kan de klant een review achterlaten. Als de klant neit is ingelogd, dan is het onmogelijk om de benodigde infomratie over de klant in te vullen
            if (isset($_SESSION['email']) && isset($_SESSION['IsSystemUser']) && $_SESSION['IsSystemUser'] !== 1) {
                //Hier wordt gekeken of de klan/gebruiker een admin is, admins kunnen niet een review achterlaten maar wel reviews verwijderen. Verder in de code wordt dit duidelijk
                ?>
                <!--De klant kan dit alleen zien als hij is ingelogd-->
                <b> <a href="review.php?artikelID=<?php echo $artikelID ?>">Laat een recensie achter!</a> </b>
            <?php } else { ?> <BR>
                <!--Als de klant niet is igelogd, wordt dit weergeven. dit leidt naar de inlog pagina-->
                <b> <a href="LoginMain.php">U moet ingelogd zijn om een recensie achter te laten</a></b>
            <?php } ?>

            <hr style="width:80%" color="red">
            <b> Reviews</b> <BR>

            <?php
//Hier worden de review uit de database gehaald die corresponderen met het artikelID
            $stmt = $conn->prepare('SELECT * FROM review WHERE StockItemID = :artikelID ORDER BY date');
            $stmt->execute(array(':artikelID' => $artikelID));
//Hier wordt de opgehaalde informatie in variabelen gestopt
            while ($data = $stmt->fetch()) {
                $PersonID = $data['PersonID'];
                $review = $data['review'];
                $reviewname = $data['reviewname'];
                $rating = $data['rating'];
                $PersonID = $data['PersonID'];
                $date = $data['date'];
                $reviewid = $data['reviewid'];
//Hier wordt de naam van de klant uit de database opgehaald
                $query = $conn->query('SELECT FullName FROM people WHERE PersonID =' . $PersonID);
                $query->execute(array(//Werkt ook niet
                    ':PersonID' => $PersonID));
//Hier wordt de naam van de klant in een variabele gezet
                $data = $query->fetch();
                $FullName = $data['FullName'];

                //Hier wordt gekeken of er reviews zijn achtergelaten. als dat zo is, dan worden de reviews die eerder werden opgehaald in een while loop gezet en onder elkaar weergeven
                try {
                    $query2 = $conn->prepare('SELECT COUNT(*) FROM review WHERE StockItemID = :artikelid');
                    $query2->execute(array(':artikelid' => $artikelID));
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

                $reviewnumber = 0;
                while ($count = $query2->fetch()) {
                    $reviewnumber += $count[0];
                }
                if ($reviewnumber > 0) {
                    ?>

                    <!--Dit is in feite de review, je hebt de naam van het review, de naam van de klant, het review zelf, de rating en de datum-->
                    <BR> <b> Review naam: <?php echo $reviewname ?> </b>
                    <BR> <b> Door <?php echo preg_replace('/([0-9])/', '', (preg_replace('/([a-z0-9])?([A-Z])/', '$1 $2', $FullName))); ?> </b>

                    <BR> Review:
                    <BR> <?php echo $review; ?>

                    <BR> <?php
                    if ($date != '') {
                        echo $date;
                    } else {
                        echo "Geen datum ingevoerd";
                    }
                    ?>

                    <BR> Beoordeling: 

                    <?php
                    //Dit is het sterrensysteem. 
                    for ($i = 0; $i < $rating; $i++) {
                        ?> <span class="fa fa-star checked"></span>
                        <?php
                    } if ($i < 5) {
                        for ($j = 5; $j > $rating; $j--) {
                            
                        }
                    }
                    ?> <BR>

                    <?php
                    //Als de gebruiker een admin is, dan heeft hij de mogelijkheid om een review te verwijderen
                    if (isset($_SESSION['IsSystemUser'])) {
                        if ($_SESSION['IsSystemUser'] == 1) {
                            ?>
                            <b> <a href="verwijderrecensie.php?reviewid=<?php echo $reviewid ?>&artikelid=<?php echo $artikelID ?>">Verwijder</a> </b>
                        <?php
                        }
                    }
                    ?>

                <?php } else { ?>
                    <b> <a href="review.php?artikelID=<?php echo $artikelID ?>">Er zijn nog geen recensies. Wees de eerste om een recensie achter te laten!</a> </b> 
                    <?php
                }
            } ?>
             
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>  