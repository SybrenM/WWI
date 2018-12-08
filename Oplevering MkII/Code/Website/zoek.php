<?php
include 'session.php';
include 'connection.php';
include 'functions.php';
$zoek = filter_input(INPUT_GET, "zoek", FILTER_SANITIZE_STRING);
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
        $zoekPagina = TRUE;
        include 'navbar.php';
        ?>

        <div class="container">

            <?php
            try {
                $search = $_GET['zoek'];
                //Hier worden artikelen opgezocht waarbij de naam, de tags of zoekdetails lijken op een bepaalde zoekterm
		$query = $conn->prepare("SELECT * FROM stockitems WHERE StockItemName LIKE ? OR SearchDetails LIKE ? OR Tags LIKE ?");

		//$query = $conn->prepare("SELECT * FROM stockitems WHERE StockItemName LIKE '% :search %' OR SearchDetails LIKE '% :search %' OR Tags LIKE '% :search %'");
                //$query = $conn->prepare("SELECT * FROM stockitems WHERE StockItemName LIKE '%" . $search . "%' OR SearchDetails LIKE '%" . $search . "%' OR Tags LIKE '%" . $search . "%'");
		$query->execute(array('%'.$search.'%', '%'.$search.'%', '%'.$search.'%'));
                $count = $query->rowCount();
                $artikelNaamTweedeKeer = '';
                while ($row = $query->fetch()) {
		   if($zoek == ''){
		   echo "Er is geen waarde ingevuld";
		   break;
		   }
                    $artikelNaam = $row["StockItemName"]; //De naam van het artikel
                    $artikelID = $row["StockItemID"]; //Het ID van het artikel
                    $artikelPrijs = $row["RecommendedRetailPrice"]; //De prijs van het artikel
                    $artikelMaat = $row["Size"]; //De eventuele maat van het artikel (is soms NULL)
                    //De functie 'like_match' die hier onder gebruikt wordt staat in functions.php
                    $Small = like_match('%S', $artikelMaat); //Hier wordt gekeken of de maat een S, XS, XXS oid is
                    $Medium = like_match('M', $artikelMaat); //Hier wordt gekeken of de maat een M is
                    $Large = like_match('%L', $artikelMaat); //Hier wordt gekeken of de maat een L, XL, XXL oid is
                    $LengteEen = like_match('%.%m', $artikelMaat); //Hier wordt gekeken of de maat iets met een niet-afgerond getal is, zoals bijv 1,5m; of 22.6m of 55.9m
                    $LengteTwee = like_match('%0m', $artikelMaat); //Hier wordt gekeken of de maat iets met een tiental is, zoals bijv 10m, 20m of 50m
                    $LengteDrie = like_match('%x%m', $artikelMaat); //Hier wordt gekeken of de maat iets heeft in m2. dus bijv 224mm x 550mm                   
                    //Hier wordt gekeken of er tussen de artikelen iets zit met een maatje S, M of L. Hiervan wordt er maar één weergegeven tussen de resultaten. De maat kan dan pas op de artikelpagina zelf worden gekozen.
                    if ($LengteEen OR $LengteTwee OR $LengteDrie) {
                        $artikelNaam = str_replace($artikelMaat, '', $artikelNaam); //Dit wordt de naam van het artikel zonder eventuele maat er achter
                        if ($artikelNaam != $artikelNaamTweedeKeer) { //Als de naam al een keer is laten zien, dan wordt het niet nog een keer laten zien (Zo krijg je van 1 soort maar 1 keer een link
                            $artikelNaamTweedeKeer = $artikelNaam;
                        } else {
                            goto a;
                        }
                    }
                    if ($Small OR $Medium OR $Large) {
                        $artikelNaam = str_replace(") " . $artikelMaat, '', $artikelNaam); //Artikelding is de naam van het artikel zonder eventuele maat er achter
                        $artikelNaam = $artikelNaam . ")"; //Hier wordt het haakje weer teruggezet, omdat deze alleen werd weggehaald om de positie te ontdekken
                        if ($artikelNaam != $artikelNaamTweedeKeer) { //Als de naam al een keer is laten zien, dan wordt het niet nog een keer laten zien (Zo krijg je van 1 soort maar 1 keer een link
                            $artikelNaamTweedeKeer = $artikelNaam;
                        } else {
                            goto a; //Dit zorgt er voor dat het laten zien van de naam wordt overgeslagen. Nu gaat de code door naar het volgende artikel
                        }
                    }
		    
		    
		
                    if (isset($artikelID)) {
                        ?>
                        <a href="artikel.php?artikelid=<?php print($artikelID . "&maatselected=FALSE"); ?>" class="artikelTekst">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <img src="artikelFoto/foto/<?php print($artikelID); ?>.jpg"  class="artikelImg">
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
                    }
                }
                if ($count == 0) { //Als er geen 1 artikel is gevonden
                    echo 'Geen zoekresultaten gevonden voor: \'' . $zoek . "'";
                }
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            ?>
        </div>





        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>
