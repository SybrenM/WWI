<?php
session_start();
require 'connection.php';
include 'functions.php';
$number = filter_input(INPUT_GET, "number", FILTER_SANITIZE_STRING);
if (isset($_POST["voornaam"])) {   //als voornaam is ingevuld op de vorige pagina dan kan deze pagina geopent worden, de else (dus als voornaam niet is ingevuld) staat onder aan de pagina en die verwijst naar index.php.
    // zo zorg je ervoor dat succes.php niet direct benaderd kan worden.

    
    
    
 $voornaam = clean($_POST['voornaam']);
        $achternaam = clean($_POST['achternaam']);
        $email = clean($_POST['email']);
        $straat = clean($_POST['straat']);
        $huisnummer = clean($_POST['huisnummer']);
        $plaats = clean($_POST['plaats']);
        $postcode = clean($_POST['postcode']);
        $telefoonnummer = clean($_POST['telefoonnummer']);
        $errMsg = '';
        $date = date("Y-m-d");
        $IDnumber = $conn->query('SELECT MAX(CustomerID) AS ID FROM customers'); //IDnumber = het hoogste CustetomerID
        $klantID = 0;
	$zero = 0;
	
        while ($number = $IDnumber->fetch()) { 
        $klantID +=  $number["ID"] + 1; //klantID word hier ingesteld doormiddel van hoogste CustomerID +1
    }
        $straatennummer = $straat . " " . $huisnummer;  
        $CustomerID = $klantID;
        $fullName = $voornaam . " " . $achternaam . " " . $klantID; //fullname is voornaam en achternaam + klantID zo zijn alle namen uniek
  
        
        if(isset($_SESSION["email"])){ //als email sessie (sessie die mee word genomen als je ingelogt bent) aanwezig is dan word er gekeken naar de volgende query
           //in deze query word gekeken of telefoonnummer, straat, huisnummer of postcode is gewijzigd ten opzichte van de database, is dit het geval dan word de update doorgevoerd. Hierbij word gekeken naar het sessie id is customerID.
           $updateData = $conn->prepare('UPDATE customers SET PhoneNumber = :PhoneNumber, DeliveryAddressLine1 = :DeliveryAddressLine1, DeliveryPostalCode = :DeliveryPostalCode WHERE CustomerID = :CustomerID');
            $updateData->execute(array('PhoneNumber' => $_POST["telefoonnummer"], 'DeliveryAddressLine1' => $straatennummer, 'DeliveryPostalCode' => $postcode, 'CustomerID' => $_SESSION["ID"]));
    }
        //CityID word bepaald doormiddel van het hoogste CityID te selecteren en dan dat +1 te doen, dit nieuwe getal word $StadID.
        $CityID = $conn->query('SELECT MAX(CityID) AS ID FROM cities');
        $StadID = 0;
        while ($number = $CityID->fetch()) {
            $StadID += $number["ID"] + 1;
        }
        
        //InvoiceID word bepaald doormiddel van het hoogste InvoiceID te selecteren en dan dat +1 te doen, dit nieuwe getal word $FactuurID.
         $InvoiceID = $conn->query('SELECT MAX(InvoiceID) AS ID FROM invoices');
        $FactuurID = 0;
        while ($nummer = $InvoiceID->fetch()) {
            $FactuurID += $nummer["ID"] + 1;
        }
        
        
           $CustomerID = $conn->query('SELECT MAX(CustomerID) AS ID FROM customers');
        $KlantID = 0;
        while ($klantNummer = $CustomerID->fetch()) {
            $KlantID += $klantNummer["ID"] + 1;
        }
        
        
            $articlesID = $conn->query('SELECT MAX(InvoiceLineID) AS ID FROM invoicelines');
        $ArtikelID = 0;
        while ($Artikelnummer = $articlesID->fetch()) {
            $ArtikelID += $Artikelnummer["ID"] + 1;
        }
        
        

        $stmt3 = $conn->prepare('SELECT CityID FROM cities WHERE CityName = :plaats LIMIT 1');
        $stmt3->execute(array(':plaats' => $plaats));
        $CityID2 = 0;
        while ($query = $stmt3->fetch()) {
            $CityID2 += $query['CityID'];
           
        }
        $stmt2 = $conn->prepare("SELECT COUNT(CityName) as City FROM cities WHERE CityName = :city");
        $stmt2->execute(array(':city' => $plaats));
        while ($query = $stmt2->fetch()) {
            $countCity = $query["City"];
        }
        if($countCity == 0 && !isset($_SESSION['email'])){
                    try {
                        $stmt100 = $conn->prepare('INSERT INTO cities (CityID, CityName, StateProvinceID)
    
                VALUES (:CityID, :CityName, :StateProvinceID)');
                        $stmt100->execute(array(
                            ':CityID' => $StadID, ':CityName' => $plaats, ':StateProvinceID' => 0));
                         
                        
                        $stmt8 = $conn->prepare('INSERT INTO invoices (InvoiceID, CustomerID, BillToCustomerID, DeliveryMethodID) VALUES (:InvoiceID, :CustomerID, :BillToCustomerID, :DeliveryMethodID)');
			$insertPeople =	$conn->prepare('INSERT INTO people (PersonID, FullName, PreferredName, IsPermittedToLogon, PhoneNumber, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson, LastEditedBy)
                                     VALUES (:ID, :naam, :voornaam, 1, :telefoonnummer, :wachtwoord, 0, 0, 0, 1)');
                        $stmt15 = $conn->prepare('INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, Phonenumber, DeliveryAddressLine1, DeliveryPostalCode, PostalAddressLine1, PostalPostalCode, LastEditedBy)
                      VALUES (:CustomerID, :CustomerName, :BillToCustomerID, :CustomerCategoryID, :DeliveryMethodID, :DeliveryCityID, :PostalCityID, :AccountOpenedDate, :Phonenumber, :DeliveryAddressLine1, :DeliveryPostalCode, :PostalAddressLine1, :PostalPostalCode, :LastEditedBy)');
			$insertPeople->execute(array ( ':ID' => $klantID, ':naam' => 'GEEN ACCOUNT', ':voornaam' => 'GEEN ACCOUNT', ':telefoonnummer' => $telefoonnummer, ':wachtwoord' => $telefoonnummer));
			$stmt15->execute(array(
                            //De ingevulde informatie wordt in de database gezet//
			    ':CustomerID' => $klantID, ':CustomerName' => $fullName, ':BillToCustomerID' => $klantID, ':CustomerCategoryID' => 1, ':DeliveryMethodID' => 1, ':DeliveryCityID' => $StadID, ':PostalCityID' => $StadID, ':AccountOpenedDate' => $date, ':Phonenumber' => $telefoonnummer, ':DeliveryAddressLine1' => $straatennummer, ':DeliveryPostalCode' => $postcode, ':PostalAddressLine1' => $straatennummer, ':PostalPostalCode' => $postcode, ':LastEditedBy' => 1));
                                               $stmt8->execute(array(':InvoiceID' => $FactuurID, ':CustomerID' => $klantID, ':BillToCustomerID' => $klantID, 'DeliveryMethodID' => 1));
		
                        
                                  $selectProducts = implode(',', $_SESSION['winkelwagen']);
                        $selectArticles = $conn->query("SELECT * FROM stockitems WHERE StockItemID IN (". $selectProducts .")");
                        $keys = array_keys($_SESSION['winkelwagen']);
                        $k = 0;
                        while($artikelen = $selectArticles->fetch()){
                           // print_r($_SESSION['aantal']);
                            $aantal = 0;
                             foreach ($_SESSION['winkelwagen'] as $key => $value) {
                                            if ($keys[$k] == $key) {
                                                $aantal += $_SESSION['aantal'][$key];
                                                }
                             }
                                          $insertArtikelen = $conn->query('INSERT INTO invoicelines(InvoiceLineID, InvoiceID, StockItemID, PackageTypeID, Quantity, UnitPrice, TaxRate) VALUES('.$ArtikelID.','.$FactuurID.','.$artikelen["StockItemID"].',7,'.$aantal.','.$artikelen["UnitPrice"].','.$artikelen["TaxRate"].')');
                            $ArtikelID++;
                            $k++;

                  
                        }
                        
                        
                        
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                    }elseif(!isset($_SESSION['email'])) {
                    try {
                      
                       $stmt8 = $conn->prepare('INSERT INTO invoices (InvoiceID, CustomerID, BillToCustomerID, DeliveryMethodID) VALUES (:InvoiceID, :CustomerID, :BillToCustomerID, :DeliveryMethodID)');
$insertPeople =	$conn->prepare('INSERT INTO people (PersonID, FullName, PreferredName, IsPermittedToLogon, PhoneNumber, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson, LastEditedBy)
                                     VALUES (:ID, :naam, :voornaam, 1, :telefoonnummer, :wachtwoord, 0, 0, 0, 1)');
                        $stmt3 = $conn->prepare('INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, Phonenumber, DeliveryAddressLine1,  DeliveryPostalCode, PostalAddressLine1, PostalPostalCode, LastEditedBy)
                      VALUES (:CustomerID, :CustomerName, :BillToCustomerID, :CustomerCategoryID, :DeliveryMethodID, :DeliveryCityID, :PostalCityID, :AccountOpenedDate, :Phonenumber, :DeliveryAddressLine1,  :DeliveryPostalCode, :PostalAddressLine1, :PostalPostalCode, :LastEditedBy)');


			$insertPeople->execute(array ( ':ID' => $klantID, ':naam' => 'GEEN ACCOUNT', ':voornaam' => 'GEEN ACCOUNT', ':telefoonnummer' => $telefoonnummer, ':wachtwoord' => $telefoonnummer));
                        $stmt3->execute(array(
                            //De ingevulde informatie wordt in de database gezet//
                            ':CustomerID' =>  $zero.$klantID, ':CustomerName' => $fullName, ':BillToCustomerID' =>  $zero.$klantID, ':CustomerCategoryID' => 1, ':DeliveryMethodID' => 1, ':DeliveryCityID' => $CityID2, ':PostalCityID' => $CityID2, ':AccountOpenedDate' => $date, ':Phonenumber' => $telefoonnummer, ':DeliveryAddressLine1' => $straatennummer, ':DeliveryPostalCode' => $postcode, ':PostalAddressLine1' => $straatennummer, ':PostalPostalCode' => $postcode, ':LastEditedBy' => 1));
                       $stmt8->execute(array(':InvoiceID' => $FactuurID, ':CustomerID' =>  $zero.$klantID, ':BillToCustomerID' =>  $zero.$klantID, 'DeliveryMethodID' => 1));

                       
                         $selectProducts = implode(',', $_SESSION['winkelwagen']);
                        $selectArticles = $conn->query("SELECT * FROM stockitems WHERE StockItemID IN (". $selectProducts .")");
                        $keys = array_keys($_SESSION['winkelwagen']);
                        $k = 0;
                        while($artikelen = $selectArticles->fetch()){
                           // print_r($_SESSION['aantal']);
                            $aantal = 0;
                             foreach ($_SESSION['winkelwagen'] as $key => $value) {
                                            if ($keys[$k] == $key) {
                                                $aantal += $_SESSION['aantal'][$key];
                                                }
                             }
                                          $insertArtikelen = $conn->query('INSERT INTO invoicelines(InvoiceLineID, InvoiceID, StockItemID, PackageTypeID, Quantity, UnitPrice, TaxRate) VALUES('.$ArtikelID.','.$FactuurID.','.$artikelen["StockItemID"].',7,'.$aantal.','.$artikelen["UnitPrice"].','.$artikelen["TaxRate"].')');
                            $ArtikelID++;
                            $k++;
          
                                        
                           // echo $ArtikelID;
                          //  echo $artikelen["StockItemID"];
                  
                        }
                        
                        
                           // header('Location: succes.php');
                       // exit;
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                }  elseif(isset($_SESSION['email'])){
                    $selectCities = $conn->prepare("SELECT COUNT(CityName) AS Tel FROM cities WHERE CityName = :plaats");
                    $selectCities->execute(array(':plaats' => $plaats));
                    $countCities = 0;
                    while($Cities = $selectCities->fetch()){
                        $countCities += $Cities["Tel"];
                    }
                    
                    if($countCities == 0){
                      $insertCities =  $conn->prepare('INSERT INTO cities (CityID, CityName, StateProvinceID)
    
                         VALUES (:CityID, :CityName, :StateProvinceID)');
                        $insertCities->execute(array(
                            ':CityID' => $StadID, ':CityName' => $plaats, ':StateProvinceID' => 0));
                       
                      $updateCustomerCity = $conn->prepare('update customers SET DeliveryCityID = :DeliveryCityID, PostalCityID = :PostalCityID WHERE CustomerID = :CustomerID');
                      $updateCustomerCity->execute(array(':DeliveryCityID' => $StadID, ':PostalCityID' => $StadID, ':CustomerID' => $_SESSION["ID"]));
                    } else{
                        $existingCity = $conn->prepare("SELECT * FROM cities WHERE CityName = :plaats");
                        $existingCity->execute(array(':plaats' => $plaats));
                        while($nameOfCity = $existingCity->fetch()){
                            $updateExistingCity = $conn->prepare('update customers set DeliveryCityID = :DeliveryCityID , PostalCityID = :PostalCityID WHERE CustomerID = :CustomerID');
                            $updateExistingCity->execute(array(':DeliveryCityID' => $nameOfCity["CityID"], ':PostalCityID' => $nameOfCity["CityID"], 'CustomerID' => $_SESSION["ID"]));
                         
                                  }
             
                        
                    }

                    $stmt8 = $conn->prepare('INSERT INTO invoices (InvoiceID, CustomerID, BillToCustomerID, DeliveryMethodID) VALUES (:InvoiceID, :CustomerID, :BillToCustomerID, :DeliveryMethodID)');
                     $stmt8->execute(array(':InvoiceID' => $FactuurID, ':CustomerID' => $_SESSION["ID"], ':BillToCustomerID' => $_SESSION["ID"], 'DeliveryMethodID' => 1));

                     
                        $selectProducts = implode(',', $_SESSION['winkelwagen']);
                        $selectArticles = $conn->query("SELECT * FROM stockitems WHERE StockItemID IN (". $selectProducts .")");
                        $keys = array_keys($_SESSION['winkelwagen']);
                        $k = 0;
                        while($artikelen = $selectArticles->fetch()){
                           // print_r($_SESSION['aantal']);
                            $aantal = 0;
                             foreach ($_SESSION['winkelwagen'] as $key => $value) {
                                            if ($keys[$k] == $key) {
                                                $aantal += $_SESSION['aantal'][$key];
                                                }
                             }
                                          $insertArtikelen = $conn->query('INSERT INTO invoicelines(InvoiceLineID, InvoiceID, StockItemID, PackageTypeID, Quantity, UnitPrice, TaxRate) VALUES('.$ArtikelID.','.$FactuurID.','.$artikelen["StockItemID"].',7,'.$aantal.','.$artikelen["UnitPrice"].','.$artikelen["TaxRate"].')');
                            $ArtikelID++;
                            $k++;
          
                                        
                           // echo $ArtikelID;
                          //  echo $artikelen["StockItemID"];
                  
                        }
                        
                    
                    
                }
                
//      $data = [
//    'CustomerID' => $klantID,
//    'CustomerName' => $fullName,
//    'BillToCustomerID' => $klantID,
//    'CustomerCategoryID' => 1,
//    'DeliveryMethodID' => 1,
//    'DeliveryCityID' => $StadID,
//    'PostalCityID' => $StadID,
//    'AccountOpenedDate' => $date,
//    'Phonenumber' => $telefoonnummer,
//    'DeliveryAddressLine1' => $straatennummer,
//    'DeliveryAddressLine2' => $DeliveryAdressline2,
//    'DeliveryPostalCode' => $postcode,
//    'PostalAddressLine1' => $straatennummer,
//    'PostalAddressLine2' => $land,
//    'PostalPostalCode' => $postcode,
//    'LastEditedBy' => 1,
//];

//$sql = "INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, Phonenumber, DeliveryAddressLine1, DeliveryAddressLine2, DeliveryPostalCode, PostalAddressLine1, PostalAddressLine2, PostalPostalCode, LastEditedBy)
//                      VALUES (:CustomerID, :CustomerName, :BillToCustomerID, :CustomerCategoryID, :DeliveryMethodID, :DeliveryCityID, :PostalCityID, :AccountOpenedDate, :Phonenumber, :DeliveryAddressLine1, :DeliveryAddressLine2, :DeliveryPostalCode, :PostalAddressLine1, :PostalAddressLine2, :PostalPostalCode, :LastEditedBy)";
//$stmt= $conn->prepare($sql);
//$stmt->execute($data);
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
  <?php include 'navbar.php'; ?>    <!-- navbar code in navbar.php -->
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <strong>Factuurnummer: </strong><?php 
                        echo $FactuurID;
        ?>
                    <strong>Status:</strong> in behandeling

                    <span class="float-right">
                        <strong>Factuurdatum: </strong><span id="datetime"></span>

                        <script>
                            var dt = new Date(); //automatiche tijd op vactuur
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
                            <div>Postcode: 1234 AB</div>
                            <div>Adres: Teststraat 23, Netherlands</div>
                            <div>Email: info@wwi.com</div>
                            <div>Telefoonnummer: +316 12345678</div>
                        </div>
   
                        <div class="col-sm-6">
                            <h6 class="mb-3">Naar:</h6>
                            <div>
                                <strong>Klantgegevens</strong>
                            </div>
                            <div>T.A.V.: <?php if (isset($_POST["voornaam"])) { //de ingevulde waardes in afrekenen.php
                            echo ($_POST["voornaam"]);}?> 
                                    <?php if (isset($_POST["achternaam"])) { 
                                    echo str_replace("-", "", (clean($_POST["achternaam"])));
                                }
                                ?></div>
                                <div>Postcode: <?php if (isset($_POST["postcode"])) {
                                    echo (clean($_POST["postcode"]));
                                }
                                ?></div>
                            <div>Adres: <?php if (isset($_POST["straat"])) {
                                    echo str_replace("-", "", (clean($_POST["straat"])));
                                }
                                ?>, <?php if (isset($_POST["plaats"])) {
                                    echo (clean($_POST["plaats"]));
                                }
                                ?></div>
                            <div>E-mail: <?php if (isset($_POST["email"])) {
                                    echo (cleanemail($_POST["email"]));
                                }
                                ?></div>
                            <div>Telefoonnummer: <?php if (isset($_POST["telefoonnummer"])) {
                                    echo (clean($_POST["telefoonnummer"]));
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

                                <div class="col-lg-3">
                                    <div><strong> Aantal: </strong> <?php
                                        foreach ($_SESSION['winkelwagen'] as $key => $value) {
//Als de session Key van winkelwagen met de opteller '$i' gelijk is aan de session Key van winkelwagen
                                            if ($keys[$i] == $key) {
                                                echo ($_SESSION['aantal'][$key]);
                                                $searchStockHolding = $conn->prepare("SELECT * FROM stockitemholdings WHERE StockitemID = :stockitemID");
                                                $searchStockHolding->execute(array(':stockitemID' => $artikelID));
                                                while($stockHolding = $searchStockHolding->fetch()){
                                                    $newValue = $stockHolding["QuantityOnHand"] - $_SESSION['aantal'][$key];
                                                 $updateQuantity = $conn->prepare("UPDATE stockitemholdings SET QuantityOnHand = :QuantityOnHand WHERE StockItemID = :stockitemID");
                                                  $updateQuantity->execute(array(':QuantityOnHand' => $newValue, ':stockitemID' => $artikelID));
                                                          
                                                } ?>
                                                                  <?php


                                            }
                                        }
                                        ?></div>
                                </div>
                              <div class="col-lg-2">
                                    <div><strong>Prijs: </strong><?php print("€" . $artikelPrijs); ?> </div>
                                </div>
                                        <?php 
                                        foreach ($_SESSION['winkelwagen'] as $key => $value) {
                                      if ($keys[$i] == $key) {
                                          $subtotaal = $artikelPrijs * $_SESSION['aantal'][$key];
                                        ?>
                                       <strong>Subtotaal: </strong><?php print("€" . number_format($subtotaal, 2, '.', '')); ?>

                                       <?php 
                                                                                        }
                                       
                                                                                        } ?>
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
                                <input type="submit" value="Terug naar de homepagina" class="btn btn-primary "> <!-- knop terug naar index.php -->
                                </form>
                            </div>
                        </div>
                </div>



                <?php
                if (isset($_POST['afreken'])) {
                    ?>

                    <form action="succes.php" method="POST">
                        <div class="container check">
                            <div>Naam: <?php echo clean($_POST["voornaam"]); ?> </div> <!-- controle vak met de ingevoerde gegevens -->
                            <div>E-mail: <?php echo cleanemail($_POST["email"]); ?> </div>
                            <div>Adres: <?php echo clean($_POST["address"]); ?> </div>

                            <div>Plaats: <?php echo clean($_POST["plaats"]); ?> </div>
                            <div>Postcode: <?php echo clean($_POST["postcode"]); ?> </div>
                            <div>Naam kaarthouder: <?php echo clean($_POST["cardname"]); ?> </div>
                            <div>IBAN: <?php echo clean($_POST["IBAN"]); ?> </div>
                            <div>Pasnummer: <?php echo clean($_POST["pasnummer"]); ?> </div>
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
 <?php }else {  header( "Location: index.php" ); //zorgt ervoor dat als je succes.php direct probeert te benaderen je index.php opent.
}
?>

</html>

