<!DOCTYPE html>
<!--Dit is de pagina waarnaar je wordt verwezen als je op de 'Register' knop klikt op de index pagina.-->
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="artikel-style.css">
        <link rel="stylesheet" type="text/css" href="style.css">

        <title>Registratie</title>
    </head>
    <?php
    include 'session.php';
    require 'connection.php';

    //Hier wordt een ID gecreeeeerd
    $IDnumber = $conn->query('SELECT MAX(PersonID) AS ID FROM people');
    $klantID = 0;
    while ($number = $IDnumber->fetch()) {
        $klantID += $number["ID"] + 1;
    }
    $errMsg = '';

    //Wanneer op de registratie-knop wordt gedrukt, wordt de onderstaande code uitgevoerd.
    if (isset($_POST['register'])) {
        if ($_POST['wachtwoord'] == $_POST['wachtwoord2']) {
//Hier wordt de ingevulde informatie opgehaald en gedefinieerd//
            $voornaam = $_POST['voornaam'];
            $achternaam = $_POST['achternaam'];
            //Het gegeven wachtwoord wordt gehashed//
            $passwordhash = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
            $email = $_POST['email'];
            $straat = $_POST['straat'];
            $huisnummer = $_POST['huisnummer'];
            $plaats = $_POST['plaats'];
            $postcode = $_POST['postcode'];
            $telefoonnummer = $_POST['telefoonnummer'];
            $fullName = $voornaam . " " . $achternaam . " " . $klantID;
            $straatennummer = $straat . " " . $huisnummer;
            $DeliveryAddressLine2 = $_POST['straat2'] . " " . $_POST['huisnummer2'] . " " . $_POST['plaats2'] . " " . $_POST['postcode2'];
$PostalAddressLine = $DeliveryAddressLine2;
    

            //Als de verplichte velden zijn leeggelaten, dan wordt een error message weergeven
            if ($voornaam == '') {
                $errMsg = "Vul voornaam in";
            }
            if ($achternaam == '') {
                $errMsg = "Vul achternaam in";
            }
            if ($passwordhash == '') {
                $errMsg = "Vul wachtwoord in";
            }
            if ($email == '') {
                $errMsg = "Vul email in";
            }

            //Hier wordt het ID voor de stad aangemaakt als deze nog niet in de database staat
            $CityID = $conn->query('SELECT MAX(CityID) AS ID FROM cities');
            $StadID = 0;
            while ($number = $CityID->fetch()) {
                $StadID += $number["ID"] + 1;
            }

            //Hier wordt gekeken of de ingevoerde stad in de database staat. 
            //0 betekent dat de stad niet in de database staat
            //1 betekent dat de stad wel in de database staat
            $stmt2 = $conn->prepare("SELECT COUNT(CityName) as City FROM cities WHERE CityName = :Stad");
            $stmt2->execute(array(':Stad' => $plaats));
            while ($query = $stmt2->fetch()) {
                $countCity = $query["City"];
            }

            //Huer wordt gecontroleerd of de ingevoerde email in de database staat. het emailadres is de primary key en kan dus niet dubbel in de database staan
            //0 betekent dat het ingevoerde emailadres niet in de database staat
            //1 betkent dat het ingevoerde emailadres wel in de database staat
            if ($errMsg == '' || $errMsg != '') {
                $stmt = $conn->prepare("SELECT COUNT(EmailAddress) as Email FROM people WHERE EmailAddress = :email");
                $stmt->execute(array(':email' => $email));
                while ($query = $stmt->fetch()) {
                    $countEmail = $query["Email"];
                }

                //Hier wordt de datum gecreeerd. de datum wordt later in de datbase gezet als de datum van wanneer het account is aangemaakt.
                $date = date("Y-m-d");

                //Als het ingevoerde emailadres niet in de database staat, dan wordt de ingevoerde gegevens in de database gezet
                if ($countEmail < 1) {
                    //Als de ingevoerde stad niet bestaat, dan wordt deze in de database gezet. in deze tabel wordt het aangemaakte id gebruikt, de stad die ingevoerd is en de provincieID die herleidt naar het land Nederland (In stateprovinces tabel) 
                    if ($countCity == 0) {
                        try {
                            //De stad wordt in de cities tabel gezet
                            $stmt100 = $conn->prepare('INSERT INTO cities (CityID, CityName, StateProvinceID)
                VALUES (:CityID, :CityName, :StateProvinceID)');
                            //Hier wordt informatie in de people tabel gezet. deze tabel is belangrijk omdat het wachtwoord erin staat
                            $stmt10 = $conn->prepare('INSERT INTO people (PersonID, FullName, PreferredName, EmailAddress, PhoneNumber, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson, LastEditedBy)
                                     VALUES (:ID, :naam, :voornaam, :email, :telefoonnummer, :wachtwoord, 0, 0, 0, 1)');
                            //hier wordt de informatie in de customers tabel gezet. deze tabel is belangrijk omdat de afleverinformatie erin staat.
                            $stmt15 = $conn->prepare('INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, Phonenumber, DeliveryAddressLine1, DeliveryAddressLine2, DeliveryPostalCode, PostalAddressLine1, PostalAddressLine2, PostalPostalCode, LastEditedBy)
                      VALUES (:CustomerID, :CustomerName, :BillToCustomerID, :CustomerCategoryID, :DeliveryMethodID, :DeliveryCityID, :PostalCityID, :AccountOpenedDate, :Phonenumber, :DeliveryAddressLine1, :DeliveryAddressLine2, :DeliveryPostalCode, :PostalAddressLine1, :PostalAddressLine2, :PostalPostalCode, :LastEditedBy)');
                            $stmt100->execute(array(
                                ':CityID' => $StadID, ':CityName' => $plaats, ':StateProvinceID' => 0));
                            $stmt10->execute(array(
                                ':ID' => $klantID, ':naam' => $fullName, ':voornaam' => $voornaam, ':email' => $email, ':telefoonnummer' => $telefoonnummer, ':wachtwoord' => $passwordhash));
                            $stmt15->execute(array(
                                //De ingevulde informatie wordt in de database gezet//
                                ':CustomerID' => $klantID, ':CustomerName' => $fullName, ':BillToCustomerID' => $klantID, ':CustomerCategoryID' => 1, ':DeliveryMethodID' => 1, ':DeliveryCityID' => $StadID, ':PostalCityID' => $StadID, ':AccountOpenedDate' => $date, ':Phonenumber' => $telefoonnummer, ':DeliveryAddressLine1' => $straatennummer, ':DeliveryAddressLine2' => $DeliveryAddressLine2, ':DeliveryPostalCode' => $postcode, ':PostalAddressLine1' => $straatennummer, ':PostalAddressLine2' => $PostalAddressLine, ':PostalPostalCode' => $postcode, ':LastEditedBy' => 1));
                            //Hier wordt de header aangepast
                            header('Location: Register.php?action=joined');
                            exit;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                    } else { //Als de ingevoerde stad al in de tabel staat, dan wordt deze niet ingevoerd maar wordt de rest van de ingevoerde informatie wel in de tabellen gezet.
                        try {
                            $stmt100 = $conn->prepare('SELECT * FROM cities WHERE CityName = :CityName');
                            $stmt100->execute(array(':CityName' => $plaats));
                            while ($query3 = $stmt100->fetch()) {
                                $StadID2 = $query3['CityID'];
                            }
                            $stmt10 = $conn->prepare('INSERT INTO people (PersonID, FullName, PreferredName, EmailAddress, PhoneNumber, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson, LastEditedBy) VALUES (:ID, :naam, :voornaam, :email, :telefoonnummer, :wachtwoord, 0, 0, 0, 1)');
                            $stmt3 = $conn->prepare('INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, Phonenumber, DeliveryAddressLine1, DeliveryAddressLine2, DeliveryPostalCode, PostalAddressLine1, PostalAddressLine2, PostalPostalCode, LastEditedBy)
                      VALUES (:CustomerID, :CustomerName, :BillToCustomerID, :CustomerCategoryID, :DeliveryMethodID, :DeliveryCityID, :PostalCityID, :AccountOpenedDate, :Phonenumber, :DeliveryAddressLine1, :DeliveryAddressLine2, :DeliveryPostalCode, :PostalAddressLine1, :PostalAddressLine2, :PostalPostalCode, :LastEditedBy)');
                            $stmt10->execute(array(
                                ':ID' => $klantID, ':naam' => $fullName, ':voornaam' => $voornaam, ':email' => $email, ':telefoonnummer' => $telefoonnummer, ':wachtwoord' => $passwordhash));
                            $stmt3->execute(array(
                                //De ingevulde informatie wordt in de database gezet//
                                ':CustomerID' => $klantID, ':CustomerName' => $fullName, ':BillToCustomerID' => $klantID, ':CustomerCategoryID' => 1, ':DeliveryMethodID' => 1, ':DeliveryCityID' => $StadID2, ':PostalCityID' => $StadID2, ':AccountOpenedDate' => $date, ':Phonenumber' => $telefoonnummer, ':DeliveryAddressLine1' => $straatennummer, ':DeliveryAddressLine2' => $DeliveryAddressLine2, ':DeliveryPostalCode' => $postcode, ':PostalAddressLine1' => $straatennummer, ':PostalAddressLine2' => $PostalAddressLine, ':PostalPostalCode' => $postcode, ':LastEditedBy' => 1));
                            //Hier wordt de header aangepast
                            header('Location: Register.php?action=joined');
                            exit;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                    }
                } else {
                    //Wanneer het ingevoerde email adres in de tabel gevonden wordt, wordt aangegeven dat deze al is geregistreerd
                    $errMsg = "Dit Emailadres is al geregistreerd";
                }
            }
        } else {
            //Als de wachtwoorden neit overeenkomen, wordt dit ook vermeld
            $errMsg = "Passwords do not match";
        }
    }
//Als op de "register' knop wordt gedrukt dan wordt een button zichtbaar die je herleid naar de homepage
    if (isset($_GET['action']) && $_GET['action'] == 'joined') {
        $errMsg = 'Registration succesful. Now you can <a href="LoginMain.php">Login</a>';
    }
    ?>

    <!DOCTYPE html>
    <body><?php include 'navbar.php'; ?>
        <div class="container">
            <h1> Registreren </h1>

            <?php
            if (isset($errMsg)) {
                echo '<div style="color:#FF0000;text-align:center;font-size:17px;">' . $errMsg . '</div>';
            }
            ?>

            <!-- Hier wordt de informatie opgehaald-->
            <form action="" method="post">
                <input type="text" name="voornaam" placeholder="Voornaam"     
                       autocomplete="off" class="box"/><?php if (isset($_POST['submit'])) { ?> <span style="color: red;"> *</span> <?php } ?> <br /><br />
                <input type="text" name="achternaam" placeholder="Achternaam" 
                       autocomplete="off" class="box"/><span style="color: red;"> *</span><br /><br />
                <input type="text" name="email" placeholder="E-Mail" 
                       autocompleter="off" class="box" /><span style="color: red;"> *</span><br/><br />
                <input type="text" name="straat" placeholder="Straat"         
                       autocomplete="off" class="box"/>
                <input type="text" name="huisnummer" placeholder="Huisnummer"         
                       autocomplete="off" class="box"/><span style="color: red;"> *</span><br /><br />
                <input type="text" name="plaats" placeholder="Plaats"         
                       autocomplete="off" class="box"/>
                <input type="text" name="postcode" placeholder="Postcode"         
                       autocomplete="off" class="box"/><span style="color: red;"> *</span><br /><br />
                <input type="text" name="telefoonnummer" placeholder="Telefoonnummer"         
                       autocomplete="off" class="box"/><span style="color: red;"> *</span><br /><br />
                <input type="password" name="wachtwoord" placeholder="Wachtwoord" 
                       class="box" /><span style="color: red;"> *</span><br/><br />                       
                <input type="password" name="wachtwoord2" placeholder="Wachtwoord herhalen" 
                       class="box" /><span style="color: red;"> *</span><br/><br />
                <input type="text" name="straat2" placeholder="Straat (Optioneel)"         
                       autocomplete="off" class="box"/><span style="color: red;"> *</span><br /><br />
                <input type="text" name="huisnummer2" placeholder="Huisnummer (Optioneel)"         
                       autocomplete="off" class="box"/><span style="color: red;"> *</span><br /><br />
                <input type="text" name="plaats2" placeholder="Plaats (Optioneel)"         
                       autocomplete="off" class="box"/><span style="color: red;"> *</span><br /><br />
                <input type="text" name="postcode2" placeholder="Postcode (Optioneel)"         
                       autocomplete="off" class="box"/><span style="color: red;"> *</span><br /><br />
                <input type="submit" class="btn btn-primary" name='register' value="Registreer" class='submit'/><br />
            </form>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>

