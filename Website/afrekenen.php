<?php
session_start();
include 'connection.php';
$number = filter_input(INPUT_GET, "number", FILTER_SANITIZE_STRING);
//if(isset($_POST["Naam"]) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['city']) && isset($_POST['zip']) && isset($_POST['land']) && isset($_POST['telefoonnummer'])){
//$_SESSION['Naam'] = $_POST['Naam'];
//$_SESSION['email'] = $_POST['email'];
//$_SESSION['address'] = $_POST['address'];
//$_SESSION['city'] = $_POST['city'];
//$_SESSION['zip'] = $_POST['zip'];
//$_SESSION['land'] = $_POST['land'];
//$_SESSION['telefoonnummer'] = $_POST['telefoonnummer'];
//}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="afrekenen-style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>

        <?php
        include 'navbar.php';
        ?>
        <div class="row">
            <div class="col-75">
                <div class="container">
                    <form action="" method="post"> 
                        <h2>Afrekenen</h2>                       <!-- tabel met placeholders en code zodat de ingevulde gegevens blijven staan nadat je op een knop hebt gedrukt. -->
                        <p>Vul de gegevens in en klik op "Door naar afrekenen" om de betaling af te ronden.</p>
                        <p>Klik op "Bestelling afbreken" om de bestelling af te breken.</p>
                        <div class="row">
                            <div class="col-50">
                                <h3>Betaal adres</h3>
                                <label for="fname"><i class="fa fa-user"></i> Naam</label>
                                <input type="text" id="fname" name="Naam" placeholder="Henk de Groot" value="<?php
                                if (isset($_POST["Naam"])) {
                                    echo ($_POST["Naam"]);
                                }
                                ?>" required>
                                <label for="email"><i class="fa fa-envelope"></i> Email</label>
                                <input type="text" id="email" name="email" placeholder="hdegroot@example.com" value="<?php
                                if (isset($_POST["email"])) {
                                    echo ($_POST["email"]);
                                }
                                ?>" required>
                                <label for="adr"><i class="fa fa-address-card-o"></i> Adres</label>
                                <input type="text" id="adr" name="address" placeholder="Dorpstraat 14" value="<?php
                                if (isset($_POST["address"])) {
                                    echo ($_POST["address"]);
                                }
                                ?>" required>
                                <label for="city"><i class="fa fa-institution"></i> Plaats</label>
                                <input type="text" id="city" name="city" placeholder="Zwolle" value="<?php
                                if (isset($_POST["city"])) {
                                    echo ($_POST["city"]);
                                }
                                ?>" required>
                                <label for="land"><i class="fa"></i> Land</label>
                                <input type="text" id="land" name="land" placeholder="Nederland" value="<?php
                                if (isset($_POST["land"])) {
                                    echo ($_POST["land"]);
                                }
                                ?>" required>

                                <div class="row">
                                    <div class="col-50">
                                        <label for="zip">Postcode</label>   
                                        <input type="text" id="zip" name="zip" placeholder="9999XY" value="<?php
                                        if (isset($_POST["zip"])) {
                                            echo ($_POST["zip"]);
                                        }
                                        ?>" required>
                                        <label for="telefoonnummer"><i class="fa"></i> Telefoonnummer</label>
                                        <input type="text" id="telefoonnummer" name="telefoonnummer" placeholder="0623478282" value="<?php
                                        if (isset($_POST["telefoonnummer"])) {
                                            echo ($_POST["telefoonnummer"]);
                                        }
                                        ?>" required>

                                    </div>
                                </div>
                            </div>

                            <div class="col-50">
                                <h3>Betaalmethode</h3>
                                <label for="fname">Geaccepteerde betaalkaarten</label>
                                <div class="icon-container">
                                    <img src="ideal3.jpg" alt="ideal"> <!-- ideal logo -->
                                </div>
                                <label for="cname">Naam kaarthouder</label>
                                <input type="text" id="cname" name="cardname" placeholder="Henk de Groot" value="<?php
                                if (isset($_POST["cardname"])) {
                                    echo ($_POST["cardname"]);
                                }
                                ?>" required>
                                <label for="ccnum">IBAN</label>
                                <input type="text" id="ccnum" name="IBAN" placeholder="NL70 RABO 0123 4567 89" value="<?php
                                if (isset($_POST["IBAN"])) {
                                    echo ($_POST["IBAN"]);
                                }
                                ?>" required>
                                <div class="row">
                                    <div class="col-50">
                                        <label for="pasnummer">Pasnummer</label>
                                        <input type="text" id="pasnummer" name="pasnummer" placeholder="3522" value="<?php
                                        if (isset($_POST["pasnummer"])) {
                                            echo ($_POST["pasnummer"]);
                                        }
                                        ?>" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Button trigger modal -->
                        <input type="submit" class="btn btn-primary btn-success afrekenen" data-target="#exampleModalCenter" name="afreken" value="Controleer uw gegevens"> <!--  afrekenen knop -->
                    </form>
                    <!-- Modal -->


                    <form action="index.php" method="POST">
                        <div class="row">
                            <div class="col-50">
                                <input type="submit" value="Betaling afbreken" class="btn btn-danger afbreken"> <!-- betaling afbreken knop -->
                                </form>
                            </div>
                        </div>
                </div>
            </div>

            <div class="col-25">
                <div class="container"> <!-- code winkelmand -->

                    <h1> Winkelmand </h1>
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
                                    <strong> Product: <BR></strong><?php
                                    print $artikelNaam;
                                    ?> 
                                </div>
                                <div class="col-lg-2">
                                    <div><strong>Prijs: <BR></strong><?php print("€" . $artikelPrijs); ?> </div>
                                </div>
                                <div class="col-lg-3">
                                    <div><strong> Aantal:<BR> </strong><?php
                                        foreach ($_SESSION['winkelwagen'] as $key => $value) {
                                            //Als de session Key van winkelwagen met de opteller '$i' gelijk is aan de session Key van winkelwagen
                                            if ($keys[$i] == $key) {
                                                echo ($_SESSION['aantal'][$key]);
                                            }
                                        }
                                        ?></div>
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
                                <div> <strong><br>Totale prijs: </strong><?php echo "€" . number_format($totalePrijs, 2); ?> </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>


                <?php
                if (isset($_POST['afreken'])) {
                    ?>

                    <form action="succes.php" method="POST"> <!-- code voor onzichtbare POST method zodat de ingevulde gegevens in succes.php weer opgevraagt kunnen worden. -->
                        <div class="container check">
                            <input type="hidden" name="Naam" value="<?php echo $_POST["Naam"]; ?>">
                            <div><strong>Naam:</strong> <?php echo $_POST["Naam"]; ?> </div> 

                            <input type="hidden" name="email" value="<?php echo $_POST["email"]; ?>">
                            <div><strong>E-mail: </strong><?php echo $_POST["email"]; ?> </div>

                            <input type="hidden" name="address" value="<?php echo $_POST["address"]; ?>">      
                            <div><strong>Adres: </strong><?php echo $_POST["address"]; ?> </div>

                            <input type="hidden" name="city" value="<?php echo $_POST["city"]; ?>">
                            <div><strong>Plaats:</strong> <?php echo $_POST["city"]; ?> </div>

                            <input type="hidden" name="zip" value="<?php echo $_POST["zip"]; ?>">        
                            <div><strong>Postcode: </strong><?php echo $_POST["zip"]; ?> </div>

                            <input type="hidden" name="cardname" value="<?php echo $_POST["cardname"]; ?>">       
                            <div><strong>Naam kaarthouder:</strong> <?php echo $_POST["cardname"]; ?> </div>

                            <input type="hidden" name="IBAN" value="<?php echo $_POST["IBAN"]; ?>">        
                            <div><strong>IBAN: </strong><?php echo $_POST["IBAN"]; ?> </div>

                            <input type="hidden" name="pasnummer" value="<?php echo $_POST["pasnummer"]; ?>">       
                            <div><strong>Pasnummer:</strong> <?php echo $_POST["pasnummer"]; ?> </div>

                            <input type="hidden" name="telefoonnummer" value="<?php echo $_POST["telefoonnummer"]; ?>">    
                            <div><strong>Telefoonnummer: </strong><?php echo $_POST["telefoonnummer"]; ?> </div>

                            <input type="hidden" name="land" value="<?php echo $_POST["land"]; ?>">    
                            <div><strong>Land:</strong> <?php echo $_POST["land"]; ?> </div>
                            <div><I>Door op de knop "Naar IDEAL betaalpagina" te klikken ga je akkoort met de bestelling en de ingevoerde gegevens.</I></div>
                            <input type="submit" class="btn btn-primary btn-success" data-target="#exampleModalCenter" name="betalen" value="Naar IDEAL betaalpagina"> <!-- knop naar succes.php -->
                            </form>
                        </div>   
 
                </div>
                <?php
            }
            ?>

        </div>

<?php
// This function will run within each post array including multi-dimensional arrays 
function ExtendedAddslash(&$params)
{ 
        foreach ($params as &$var) {
            // check if $var is an array. If yes, it will start another ExtendedAddslash() function to loop to each key inside.
            is_array($var) ? ExtendedAddslash($var) : $var=addslashes($var);
            unset($var);
        }
} 

// Initialize ExtendedAddslash() function for every $_POST variable
ExtendedAddslash($_POST);      

$naam= $_POST['Naam']; 
$address = $_POST['address'];
$zip = $_POST['zip'];
$stad = $_POST['stad'];
$land = $_POST['land'];
mysql_query("INSERT INTO `factuur` (klantnaam, adres, postcode, stad, land) 
                               VALUES ('$naam', '$address', '$zip', '$stad', '$land') ") 
?>



        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>