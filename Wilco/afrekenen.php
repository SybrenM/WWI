<?php
session_start();
    require 'connection.php';
$number = filter_input(INPUT_GET, "number", FILTER_SANITIZE_STRING);
//if(isset($_POST["Naam"]) && isset($_POST['email']) && isset($_POST['straat']) && isset($_POST['plaats']) && isset($_POST['postcode']) && isset($_POST['land']) && isset($_POST['telefoonnummer'])){
//$_SESSION['Naam'] = $_POST['Naam'];
//$_SESSION['email'] = $_POST['email'];
//$_SESSION['straat'] = $_POST['straat'];
//$_SESSION['plaats'] = $_POST['plaats'];
//$_SESSION['postcode'] = $_POST['postcode'];
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
 if (isset($_POST["afrekenen"])) {
        $voornaam = $_POST['voornaam'];
        $achternaam = $_POST['achternaam'];
        $email = $_POST['email'];
        $straat = $_POST['straat'];
        $huisnummer = $_POST['huisnummer'];
        $plaats = $_POST['plaats'];
        $postcode = $_POST['postcode'];
        $telefoonnummer = $_POST['telefoonnummer'];
        $fullName = $voornaam . " " . $achternaam . " " . $klantID;
        $straatennummer = $straat . " " . $huisnummer;
        $errMsg = '';
 }
    ?>

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
                                <label for="fname"><i class="fa fa-user"></i> Voornaam</label>
                                <input type="text" id="fname" name="voornaam" placeholder="Henk " value="<?php
                                if (isset($_POST["voornaam"])) {
                                    echo ($_POST["voornaam"]);
                                }
                                ?>" required>
                                <label for="aname"><i class="fa fa-user"></i> Achternaam</label>
                                <input type="text" id="fname" name="achternaam" placeholder="de Groot" value="<?php
                                if (isset($_POST["achternaam"])) {
                                    echo ($_POST["achternaam"]);
                                }
                                ?>" required>
                                <label for="email"><i class="fa fa-envelope"></i> Email</label>
                                <input type="text" id="email" name="email" placeholder="hdegroot@example.com" value="<?php
                                if (isset($_POST["email"])) {
                                    echo ($_POST["email"]);
                                }
                                ?>" required>
                                <label for="adr"><i class="fa fa-straat-card-o"></i> Adres</label>
                                <input type="text" id="adr" name="straat" placeholder="Dorpstraat" value="<?php
                                if (isset($_POST["straat"])) {
                                    echo ($_POST["straat"]);
                                }
                                ?>" required>
                                                              <label for="land"><i class="fa"></i> Huisnummer</label>
                                <input type="text" id="huisnummer" name="huisnummer" placeholder="12" value="<?php
                                if (isset($_POST["huisnummer"])) {
                                    echo ($_POST["huisnummer"]);
                                }
                                ?>" required>
                                <label for="plaats"><i class="fa"></i> Plaats</label>
                                <input type="text" id="plaats" name="plaats" placeholder="Zwolle" value="<?php
                                if (isset($_POST["plaats"])) {
                                    echo ($_POST["plaats"]);
                                }
                                ?>" required>
                                <div class="row">
                                    <div class="col-50">
                                        <label for="postcode">Postcode</label>   
                                        <input type="text" id="postcode" name="postcode" placeholder="9999XY" value="<?php
                                        if (isset($_POST["postcode"])) {
                                            echo ($_POST["postcode"]);
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
                            <input type="hidden" name="voornaam" value="<?php echo $_POST["voornaam"]; ?>">
                            <div><strong>Voornaam:</strong> <?php echo $_POST["voornaam"]; ?> </div> 

                             <input type="hidden" name="achternaam" value="<?php echo $_POST["achternaam"]; ?>">
                            <div><strong>Achternaam:</strong> <?php echo $_POST["achternaam"]; ?> </div> 
                            
                            <input type="hidden" name="email" value="<?php echo $_POST["email"]; ?>">
                            <div><strong>E-mail: </strong><?php echo $_POST["email"]; ?> </div>

                            <input type="hidden" name="straat" value="<?php echo $_POST["straat"]; ?>">      
                            <div><strong>Adres: </strong><?php echo $_POST["straat"]; ?> </div>
                            
                            <input type="hidden" name="huisnummer" value="<?php echo $_POST["huisnummer"]; ?>">    
                            <div><strong>Huisnummer:</strong> <?php echo $_POST["huisnummer"]; ?> </div>

                            <input type="hidden" name="plaats" value="<?php echo $_POST["plaats"]; ?>">
                            <div><strong>Plaats:</strong> <?php echo $_POST["plaats"]; ?> </div>

                            <input type="hidden" name="postcode" value="<?php echo $_POST["postcode"]; ?>">        
                            <div><strong>Postcode: </strong><?php echo $_POST["postcode"]; ?> </div>

                            <input type="hidden" name="cardname" value="<?php echo $_POST["cardname"]; ?>">       
                            <div><strong>Naam kaarthouder:</strong> <?php echo $_POST["cardname"]; ?> </div>

                            <input type="hidden" name="IBAN" value="<?php echo $_POST["IBAN"]; ?>">        
                            <div><strong>IBAN: </strong><?php echo $_POST["IBAN"]; ?> </div>

                            <input type="hidden" name="pasnummer" value="<?php echo $_POST["pasnummer"]; ?>">       
                            <div><strong>Pasnummer:</strong> <?php echo $_POST["pasnummer"]; ?> </div>

                            <input type="hidden" name="telefoonnummer" value="<?php echo $_POST["telefoonnummer"]; ?>">    
                            <div><strong>Telefoonnummer: </strong><?php echo $_POST["telefoonnummer"]; ?> </div>


                            <div><I>Door op de knop "Naar IDEAL betaalpagina" te klikken ga je akkoort met de bestelling en de ingevoerde gegevens.</I></div>
                            <input type="submit" class="btn btn-primary btn-success" data-target="#exampleModalCenter" name="betalen" value="Naar IDEAL betaalpagina"> <!-- knop naar succes.php -->
                            </form>
                        </div>   
 
                </div>
                <?php
            }
            ?>

        </div>




        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>