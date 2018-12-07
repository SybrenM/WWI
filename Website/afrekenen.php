<?php
include 'session.php';
    require 'connection.php';
    include 'functions.php';
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

 if (isset($_POST["afrekenen"])) { //als er op de knop afrekenen is gedrukt worden de volgende values ingeschakelt
        
     //$uncleanVoornaam = $_POST['voornaam'];
     
        $voornaam = clean($$_POST['voornaam']); 
        $achternaam = clean($_POST['achternaam']);
        $email = cleanemail($_POST['email']);
        $straat = clean($_POST['straat']);
        $huisnummer = clean($_POST['huisnummer']);
        $plaats = clean($_POST['plaats']);
        $postcode = clean($_POST['postcode']);
        $telefoonnummer = clean($_POST['telefoonnummer']);
        $fullName = $voornaam . " " . $achternaam . " " . $klantID;
        $straatennummer = $straat . " " . $huisnummer;
        $errMsg = '';
        
 }
 
 if(isset($_SESSION['email'])) { //als de sessie email (de enige sessie die er is als je ingelogt bent) is ingesteld dan  word de volgende query in werking gezet.
     $stmt = $conn->prepare('SELECT * FROM customers JOIN people ON people.PersonID = customers.CustomerID JOIN cities ON cities.CityID = customers.DeliveryCityID WHERE CustomerID = :CustomerID'); //hier worden alle gegevens voorbereid van het ophalen van de ingelogte gebruiker uit de tabellen customers, people en cities.
     $stmt->execute(array(':CustomerID' => $_SESSION['ID'])); //hier word de query in werking gezet met :CustomerID is sessie ID. Dit ID word bepaald bij het inloggen meer hierover staat in LoginMain.php.
     while ($data = $stmt->fetch()) {
         $FullName = $data['CustomerName']; //$Fullname word hier bepaald met de gegevens van curstomers.CustomerName uit de database.
        // echo print_r($data);
       //  echo $data["PreferredName"];

 
    ?>

        <?php
        include 'navbar.php'; //navbar.php word toegevoegd aan deze pagina.
        ?>
        <?php if (isset($_SESSION['email'])) { ?> <!-- als de sessie email (de enige sessie die er is als je ingelogt bent) is ingesteld dan  word de volgende query in werking gezet. -->
        <div class="row">
            <div class="col-75">
                <div class="container">
                    <form action="" method="post"> <!-- De $_POST methode word gebruikt in dit tabel. -->
                        <h2>Afrekenen</h2>                       <!-- tabel met placeholders en code zodat de ingevulde gegevens blijven staan nadat je op een knop hebt gedrukt. -->
                        <p>Vul de gegevens in en klik op "Door naar afrekenen" om de betaling af te ronden.</p>  <!-- Titels boven het formulier. -->
                        <p>Klik op "Bestelling afbreken" om de bestelling af te breken.</p>
                        <div class="row">
                            <div class="col-50">
                                <h3>Betaal adres</h3>
                                <label for="fname"><i class="fa fa-user"></i> Voornaam</label>
                                <input type="text" id="fname" name="voornaam" pattern="[A-Za-z]"  placeholder="Henk " value="<?php
                                    if(isset($data["PreferredName"])){ //Als PreferredName is ingevuld en gevonden door de uitgevonden query hierboven dan kunnen de volgende echo's uitgevoerd worden.
                                    echo ($data['PreferredName']);
                                    }
                                
                                ?>"  readonly> <!-- De ingevulde waardes die uit de database komen mogen in dit geval niet veranderd worden om fouten te voorkomen, dit mag bij adres wel. -->
                                <label for="aname"><i class="fa fa-user"></i> Achternaam</label>
                                <input type="text" id="fname" name="achternaam" pattern="[A-Za-z]"  placeholder="de Groot" value="<?php
                                                                   
                                $different = str_replace($data["PreferredName"], '', $data['FullName']); //filtert de voornaam uit de voledige naam zodat alleen de achternaam word weergegeven.
                                echo preg_replace('/[0-9]+/', '', $different); //Fullname is uniek gesteld door het CustomerID er achter te plakken, door dit stuk code word het CustomerID eruit gefiltert en word dus alleen de achternaam weergegeven.
                               
                                
                                ?>" readonly>
                                <label for="email"><i class="fa fa-envelope"></i> Email</label>
                                <input type="text" id="email" name="email" pattern="[A-Za-z0-9-@.]" placeholder="hdegroot@example.com" value="<?php
                                    echo ($_SESSION['email']); // de sessie email is aanwezig als je ingelogt bent en kan dus zo weergegeven worden.
                                
                                ?>" readonly>
                                <label for="adr"><i class="fa fa-straat-card-o"></i> Straat</label>
                                <input type="text" id="adr" name="straat" pattern="[A-Za-z]" placeholder="Dorpstraat" value="<?php
                                    echo str_replace("-", "", preg_replace('/[0-9]+/', '', $data['DeliveryAddressLine1'])); // zelfde geld voor achternaam, in deliveryline1 word straat en huisnummer opgeslagen en dus filteren we het huisnummer uit de data.
                                
                                ?>" required>
                                <label for="land"><i class="fa"></i> Huisnummer</label>
                                <input type="text" id="huisnummer"  pattern="[A-Za-z0-9]" name="huisnummer" placeholder="12" value="<?php
                                    echo preg_replace('/\D/', '',$data['DeliveryAddressLine1']); //in deliveryline1 word straat en huisnummer opgeslagen, hier worden de letters eruit gefiltert zodat je alleen huisnummer overhoud.
                                
                                ?>" required> <!-- De volgende vakken mogen niet leeg zijn maar mogen wel gewijzigd worden, dit is omdat klanten wel eens kunnen verhuizen, in succes.php word laten zien hoe de wijzigingen doorgevoerd worden in de database. -->
                                <label for="plaats"><i class="fa"></i> Plaats</label>
                                <input type="text" id="plaats" name="plaats" pattern="[A-Za-z]" placeholder="Zwolle" value="<?php
                                    echo ($data['CityName']); //in de query was ook een join van cities meegenomen, tijdens de registratie is het CityID bepaald en de daarbij horen de CityName. Hoe dit precies word bepaalt kun je vinden in de documentatie van de registratie.
                                
                                ?>" required>
                                <div class="row">
                                    <div class="col-50">
                                        <label for="postcode">Postcode</label>   
                                        <input type="text" id="postcode" name="postcode" pattern="[A-Za-z0-9]" placeholder="9999XY" value="<?php
                                    echo ($data['DeliveryPostalCode']); //postcode word geprint uit de query, hier geld ook voor dat het vak niet leeg mag zijn maar wel gewijzigd kan worden.
                                
                                       ?>" required>
                                        <label for="telefoonnummer"><i class="fa"></i> Telefoonnummer</label>
                                        <input type="text" id="telefoonnummer" name="telefoonnummer" pattern="[0-9]" placeholder="0623478282" value="<?php
                                        echo ($data['16']); //telefoonnummer word hier geprint uit de query, hier geld ook voor dat het vak niet leeg mag zijn maar wel gewijzigd kan worden.
                                        
                                        ?>" required>
 <?php } }?>
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
                                <input type="text" id="cname" name="cardname" pattern="[A-Za-z]+" placeholder="Henk de Groot" value="<?php
                                if (isset($_POST["cardname"])) { //De ingevulde waarden blijven staan zodra je op een knop drukt, zo hou je overzicht in je gegevens.
                                    echo ($_POST["cardname"]); //de $_POST methode zorgt ervoor dat de ingevulde gegevens mee worden genomen naar de volgende pagina zodat de waardes ook op de volgende pagina te printen zijn.
                                } // dit veld is verplicht (mag niet leeg zijn)
                                ?>" required>
                                <label for="ccnum">IBAN</label>
                                <input type="text" id="ccnum" name="IBAN" pattern="[A-Za-z0-9]+" placeholder="NL70 RABO 0123 4567 89" value="<?php
                                if (isset($_POST["IBAN"])) {
                                    echo ($_POST["IBAN"]); //zie cardname
                                }
                                ?>" required>
                                <div class="row">
                                    <div class="col-50">
                                        <label for="pasnummer">Pasnummer</label>
                                        <input type="text" id="pasnummer" name="pasnummer" pattern="[A-Z0-9]+" placeholder="3522" value="<?php
                                        if (isset($_POST["pasnummer"])) {
                                            echo ($_POST["pasnummer"]); //zie cardname
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
                                <input type="submit" value="Betaling afbreken" class="btn btn-danger afbreken"> <!-- betaling afbreken knop, hiermee word je teruggebracht naar index.php (homepagina) -->
                                </form>
                            </div>
                        </div>
                </div>
            </div>
 <?php } else{    //als de sessie email niet aanwezig is (niet ingelogt dus) dan word het volgende formulier weergegeven zonder ingevulde waardes.
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
                                <input type="text" id="fname" name="voornaam" pattern="[A-Za-z0-9]+" placeholder="Henk " value="<?php
                                if (isset($_POST["voornaam"])) { //De ingevulde waarden blijven staan zodra je op een knop drukt, zo hou je overzicht in je gegevens.
                                echo (clean($_POST["voornaam"])); //de $_POST methode zorgt ervoor dat de ingevulde gegevens mee worden genomen naar de volgende pagina zodat de waardes ook op de volgende pagina te printen zijn.
                                        }                 // dit veld is verplicht (mag niet leeg zijn), standaard staat er een placeholder in het vak maar die niet meegenomen als waarde.
                                ?>" required>
                                <label for="aname"><i class="fa fa-user"></i> Achternaam</label>
                                <input type="text" id="fname" name="achternaam" pattern="[A-Za-z0-9]+" placeholder="de Groot" value="<?php
                                if (isset($_POST["achternaam"])) {
                                echo (clean($_POST["achternaam"])); //zie de informatie bij het veld voornaam
                                        }
                                ?>" required>
                                <label for="email"><i class="fa fa-envelope"></i> Email</label>
                                <input type="text" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="hdegroot@example.com" value="<?php
                                if (isset($_POST["email"])) { //zie de informatie bij het veld voornaam
                                echo (cleanemail($_POST["email"]));
                                        }

                                ?>" required>
                                <label for="adr"><i class="fa fa-straat-card-o"></i> Adres</label>
                                <input type="text" id="adr" name="straat" pattern="[A-Za-Z]+" placeholder="Dorpstraat" value="<?php
                                if (isset($_POST["straat"])) { //zie de informatie bij het veld voornaam
                                echo (clean($_POST["straat"]));
                                        }

 
                                ?>" required>
                                <label for="land"><i class="fa"></i> Huisnummer</label>
                                <input type="text" id="huisnummer" name="huisnummer"  pattern="[0-9]+" placeholder="12" value="<?php
                                 if (isset($_POST["huisnummer"])) { //zie de informatie bij het veld voornaam
                                echo (clean($_POST["huisnummer"]));
                                        }

                                ?>" required>
                                <label for="plaats"><i class="fa"></i> Plaats</label>
                                <input type="text" id="plaats" name="plaats" pattern="[A-Za-z]+" placeholder="Zwolle" value="<?php
                                         if (isset($_POST["plaats"])) { //zie de informatie bij het veld voornaam
                                echo (clean($_POST["plaats"]));
                                        }

                                ?>" required>
                                <div class="row">
                                    <div class="col-50">
                                        <label for="postcode">Postcode</label>   
                                        <input type="text" id="postcode" name="postcode" pattern="[A-Za-z0-9]+" placeholder="9999XY" maxlength="6" value="<?php
                                if (isset($_POST["postcode"])) { //zie de informatie bij het veld voornaam
                                echo (clean($_POST["postcode"]));
                                        }

                                       ?>" required>
                                        <label for="telefoonnummer"><i class="fa"></i> Telefoonnummer</label>
                                        <input type="text" id="telefoonnummer" name="telefoonnummer" pattern="[0-9-+-]+" placeholder="0623478282" value="<?php
                                 if (isset($_POST["telefoonnummer"])) { //zie de informatie bij het veld voornaam
                                echo (clean($_POST["telefoonnummer"]));
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
                                <input type="text" id="cname" name="cardname" pattern="[A-Za-z]+" placeholder="Henk de Groot" value="<?php
                                if (isset($_POST["cardname"])) {
                                    echo (clean($_POST["cardname"])); //zie de informatie bij het veld voornaam
                                }
                                ?>" required>
                                <label for="ccnum">IBAN</label>
                                <input type="text" id="ccnum" name="IBAN" pattern="[A-Za-z0-9]+" placeholder="NL70 RABO 0123 4567 89" value="<?php
                                if (isset($_POST["IBAN"])) { //zie de informatie bij het veld voornaam
                                    echo (clean($_POST["IBAN"]));
                                }
                                ?>" required>
                                <div class="row">
                                    <div class="col-50">
                                        <label for="pasnummer">Pasnummer</label>
                                        <input type="text" id="pasnummer" name="pasnummer" pattern="[0-9]+" placeholder="3522" maxlength="4" value="<?php
                                        if (isset($_POST["pasnummer"])) { //zie de informatie bij het veld voornaam
                                            echo (clean($_POST["pasnummer"]));
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
 <?php } ?>
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
                                <div class="col-lg-2">
                                    <div><strong>Prijs: <BR></strong><?php print("€" . $artikelPrijs); ?> </div>
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
                    
        //$cleanvoornaam = clean($_POST["voornaam"]);
//als er op de knop afrekenen is gedrukt dan is het mogelijk om dit veld op te vragen.
                    ?>

                    <form action="succes.php" method="POST"> <!-- Controlleer je gegevens veld, hier worden de ingevulde waarden laten zien. -->
                        <div class="container check">
                            <input type="hidden" name="voornaam" value="<?php echo clean($_POST["voornaam"]); ?>">
                            <div><strong>Voornaam:</strong> <?php echo clean($_POST["voornaam"]); ?> </div> 

                             <input type="hidden" name="achternaam" value="<?php echo clean($_POST["achternaam"]); ?>">
                            <div><strong>Achternaam:</strong> <?php echo str_replace("-", "", clean($_POST["achternaam"])); ?> </div> 
                            
                            <input type="hidden" name="email" value="<?php echo cleanemail($_POST["email"]); ?>">
                            <div><strong>E-mail: </strong><?php echo cleanemail($_POST["email"]); ?> </div>

                            <input type="hidden" name="straat" value="<?php echo clean($_POST["straat"]); ?>">      
                            <div><strong>Adres: </strong><?php echo str_replace("-", "", clean($_POST["straat"])); ?> </div>
                            
                            <input type="hidden" name="huisnummer" value="<?php echo clean($_POST["huisnummer"]); ?>">    
                            <div><strong>Huisnummer:</strong> <?php echo clean($_POST["huisnummer"]); ?> </div>

                            <input type="hidden" name="plaats" value="<?php echo clean($_POST["plaats"]); ?>">
                            <div><strong>Plaats:</strong> <?php echo clean($_POST["plaats"]); ?> </div>

                            <input type="hidden" name="postcode" value="<?php echo clean($_POST["postcode"]); ?>">        
                            <div><strong>Postcode: </strong><?php echo clean($_POST["postcode"]); ?> </div>

                            <input type="hidden" name="cardname" value="<?php echo clean($_POST["cardname"]); ?>">       
                            <div><strong>Naam kaarthouder:</strong> <?php echo clean($_POST["cardname"]); ?> </div>

                            <input type="hidden" name="IBAN" value="<?php echo clean($_POST["IBAN"]); ?>">        
                            <div><strong>IBAN: </strong><?php echo clean($_POST["IBAN"]); ?> </div>

                            <input type="hidden" name="pasnummer" value="<?php echo clean($_POST["pasnummer"]); ?>">       
                            <div><strong>Pasnummer:</strong> <?php echo clean($_POST["pasnummer"]); ?> </div>

                            <input type="hidden" name="telefoonnummer" value="<?php echo clean($_POST["telefoonnummer"]); ?>">    
                            <div><strong>Telefoonnummer: </strong><?php echo clean($_POST["telefoonnummer"]); ?> </div>


                            <div><I>Door op de knop "Naar IDEAL betaalpagina" te klikken ga je akkoort met de bestelling en de ingevoerde gegevens.</I></div>
                            <input type="submit" class="btn btn-primary btn-success" data-target="#exampleModalCenter" name="betalen" value="Naar IDEAL betaalpagina"> <!-- knop naar succes.php -->
                            </form>
                        </div>   
 
                </div>
                <?php
            } //sluiten van de if isset
            ?>

        </div>




        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>