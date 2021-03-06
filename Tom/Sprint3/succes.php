<?php
session_start();
include 'connection.php';
$number = filter_input(INPUT_GET, "number", FILTER_SANITIZE_STRING);
 if (isset($_POST["Naam"])) {                              
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="artikel-style.css">
        <link rel="stylesheet" type="text/css" href="style.css">




        <title>Factuur</title>
    </head>
    <body>
  <?php include 'navbar.php'; ?>

        <div class="container">
            <div class="card">
                <div class="card-header">
                    <strong>Factuurnummer: </strong><?php
                    $factuurnummer = rand(100000000, 999999999);
                    print($factuurnummer . " ");
                    ?>
                    <strong>Status:</strong> in behandeling

                    <span class="float-right">
                        <strong>Factuurdatum: </strong><span id="datetime"></span>

                        <script>
                            var dt = new Date();
                            document.getElementById("datetime").innerHTML = (("0" + dt.getDate()).slice(-2)) + "." + (("0" + (dt.getMonth() + 1)).slice(-2)) + "." + (dt.getFullYear()) + " " + (("0" + dt.getHours()).slice(-2)) + ":" + (("0" + dt.getMinutes()).slice(-2));
                        </script> 
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="mb-3">Van:</h6>
                            <div>
                                <strong>Wide World Importers</strong>
                            </div>
                            <div>Teststraat 23</div>
                            <div>1234 AB, Netherlands</div>
                            <div>Email: info@wwi.com</div>
                            <div>Telefoonnummer: +316 12345678</div>
                        </div>

                        <div class="col-sm-6">
                            <h6 class="mb-3">Naar:</h6>
                            <div>
                                <strong>Klantgegevens</strong>
                            </div>
                            <div>T.A.V.: <?php if (isset($_POST["Naam"])) {
                                    echo ($_POST["Naam"]);
                                }
                                ?></div>
                            <div>Adres: <?php if (isset($_POST["address"])) {
                                    echo ($_POST["address"]);
                                }
                                ?>, <?php if (isset($_POST["city"])) {
                                    echo ($_POST["city"]);
                                }
                                ?></div>
                            <div>Land:    <?php if (isset($_POST["land"])) {
                                    echo ($_POST["land"]);
                                }
                                ?></div>
                            <div>E-mail: <?php if (isset($_POST["email"])) {
                                    echo ($_POST["email"]);
                                }
                                ?></div>
                            <div>Telefoonnummer: <?php if (isset($_POST["telefoonnummer"])) {
                                    echo ($_POST["telefoonnummer"]);
                                }
                                ?></div>
                        </div>



                    </div>





                    <div>
                        <br/><br/>
                        <strong>Bestelde producten:</strong>
                    </div>
                    <?php
// Als session leeg is, maak een nieuwe array
                    if (empty($_SESSION['winkelwagen'])) {
                        $_SESSION['winkelwagen'] = array();
                    }
                    if (empty($_SESSION['aantal'])) {
                        $_SESSION['aantal'] = array();
                    }


// Als artikel  aantal groter dan 1 is, pushen we het aantal en artikel in een session array
                    if (isset($_POST['artikelid']) && isset($_POST['number']) && $_POST['number'] >= 1) {
                        array_push($_SESSION['winkelwagen'], $_POST['artikelid']);
                        array_push($_SESSION['aantal'], $_POST['number']);
                        asort($_SESSION['winkelwagen']);
                    } elseif (isset($_POST['number']) && $_POST['number'] < 1) {
                        echo 'Aantal moet groter dan 0 zijn';
                    }


                    if (!empty($_SESSION['winkelwagen'])) {
                        $selectProducts = implode(',', $_SESSION['winkelwagen']);
                        $row = $conn->query("SELECT * FROM stockitems SI JOIN suppliers S on SI.supplierID = S.supplierID WHERE SI.stockitemid IN (" . $selectProducts . ")");
                        $i = 0;   //opteller key van Session array
                        $totalePrijs = 0;

                        $keys = array_keys($_SESSION['winkelwagen']);
                        asort($_SESSION['winkelwagen']); //waardes sorteren in Session array "winkelwagen"

                        while ($artikel = $row->fetch(PDO::FETCH_ASSOC)) {
                            $artikelNaam = $artikel["StockItemName"];
                            $artikelID = $artikel["StockItemID"];
                            $artikelPrijs = $artikel["RecommendedRetailPrice"];

                            foreach ($_SESSION['winkelwagen'] as $key => $value) {
//Als de session Key van winkelwagen met de opteller '$i' gelijk is aan de session Key van winkelwagen
                                if ($keys[$i] == $key) {
                                    $totalePrijs += $artikelPrijs * $_SESSION['aantal'][$key];  //totale prijs berekening
                                }
                            }
                            ?>


                            <div class="row">
                                <div class="col-lg-4">
                                    Product: <?php
                                    print $artikelNaam;
                                    ?> 
                                </div>
                                <div class="col-lg-2">
                                    <div>Prijs: <?php print("€" . $artikelPrijs); ?> </div>
                                </div>
                                <div class="col-lg-3">
                                    <div> Aantal: <?php
                                        foreach ($_SESSION['winkelwagen'] as $key => $value) {
//Als de session Key van winkelwagen met de opteller '$i' gelijk is aan de session Key van winkelwagen
                                            if ($keys[$i] == $key) {
                                                echo ($_SESSION['aantal'][$key]);
                                            }
                                        }
                                        ?></div>
                                </div>
                                <div class="col-lg-3">

                                </div>
                            </div>

                            <?php
                            $i++;
                        }   //sluiting while loop
                    }
                    ?>
                    <?php if (isset($totalePrijs)) { ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div><strong> Totale prijs: <?php echo "€" . number_format($totalePrijs, 2); ?></strong></div>
                            </div>
                        </div>
                    <?php } ?>
                       
                    <form action="index.php" method="POST">
                        <div class="row float-right">
                            <div class="col-50">
                                <input type="submit" value="Terug naar de homepagina" class="btn btn-primary ">
                                </form>
                            </div>
                        </div>
                </div>



                <?php
                if (isset($_POST['afreken'])) {
                    ?>

                    <form action="succes.php" method="POST">
                        <div class="container check">
                            <div>Naam: <?php echo $_POST["Naam"]; ?> </div> 
                            <div>E-mail: <?php echo $_POST["email"]; ?> </div>
                            <div>Adres: <?php echo $_POST["address"]; ?> </div>

                            <div>Plaats: <?php echo $_POST["city"]; ?> </div>
                            <div>Postcode: <?php echo $_POST["zip"]; ?> </div>
                            <div>Naam kaarthouder: <?php echo $_POST["cardname"]; ?> </div>
                            <div>IBAN: <?php echo $_POST["IBAN"]; ?> </div>
                            <div>Pasnummer: <?php echo $_POST["pasnummer"]; ?> </div>
                            <input type="submit" class="btn btn-primary btn-success" data-target="#exampleModalCenter" name="betalen" value="Naar IDEAL betaalpagina">
                                
                        </div>     
                </div>
                <?php
            }
            ?>

        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
<?php    } else {  header( "Location: index.php" ); 
}
?>
</html>

