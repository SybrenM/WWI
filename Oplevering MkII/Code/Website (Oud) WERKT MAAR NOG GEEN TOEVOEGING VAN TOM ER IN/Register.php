<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="artikel-style.css">
        <link rel="stylesheet" type="text/css" href="style.css">

        <title>Hello, world!</title>
    </head>
    <?php
//Dit is de pagina waarnaar je wordt verwezen als je op de 'Register' knop klikt op de index pagina.
    include 'session.php';
    require 'connection.php';

    //Hier wordt een stadId aangemaakt
    //Hier wordt een ID gecreeeeerd//
    $IDnumber = $conn->query('SELECT MAX(PersonID) AS ID FROM people');
    $klantID = 0;
    while ($number = $IDnumber->fetch()) {
        $klantID += $number["ID"] + 1;
    }
    

    
    
    

    if (isset($_POST['register'])) {
    $wachtwoord = $_POST["wachtwoord"];
$wachtwoord2 = $_POST["wachtwoord2"];
            if($wachtwoord == $wachtwoord2) {
        $errMsg = '';
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

        $CityID = $conn->query('SELECT MAX(CityID) AS ID FROM cities');
        $StadID = 0;
        while ($number = $CityID->fetch()) {
            $StadID += $number["ID"] + 1;
        }

        $stmt3 = $conn->prepare('SELECT CityID FROM cities WHERE CityName = :plaats LIMIT 1');
        $stmt3->execute(array(':plaats' => $plaats));
        $CityID2 = 0;
        while ($query = $stmt3->fetch()) {
            $CityID2 += $query['CityID'];
            echo $query["CityID"];
        }
        $stmt2 = $conn->prepare("SELECT COUNT(CityName) as City FROM cities WHERE CityName = :city");
        $stmt2->execute(array(':city' => $plaats));
        while ($query = $stmt2->fetch()) {
            $countCity = $query["City"];
        }



        if ($errMsg == '') {
            $stmt = $conn->prepare("SELECT COUNT(EmailAddress) as Email FROM people WHERE EmailAddress = :email");
            $stmt->execute(array(':email' => $email));
            while ($query = $stmt->fetch()) {
                $countEmail = $query["Email"];
            }

            $date = date("Y-m-d");
            if ($countEmail < 1) {

//                
//            try {
//                
//               
//                exit;
//            } catch (PDOException $e) {
//                echo $e->getMessage();
//            }
//        } else {
//           // echo "City already exists. Entry into database redundant";
//        }
                ECHO $CityID2;

                if ($countCity == 0) {
                    
                    
                    
                    
                    echo "City does not exist";
                    try {
                        $stmt100 = $conn->prepare('INSERT INTO cities (CityID, CityName, StateProvinceID)
    
                VALUES (:CityID, :CityName, :StateProvinceID)');
                        $stmt100->execute(array(
                            ':CityID' => $StadID, ':CityName' => $plaats, ':StateProvinceID' => 0));
                        $stmt10 = $conn->prepare('INSERT INTO people (PersonID, FullName, EmailAddress, PhoneNumber, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson, LastEditedBy) VALUES (:ID, :naam, :email, :telefoonnummer, :wachtwoord, 0, 0, 0, 1)');
                        $stmt15 = $conn->prepare('INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, Phonenumber, DeliveryAddressLine1, DeliveryAddressLine2, DeliveryPostalCode, PostalAddressLine1, PostalAddressLine2, PostalPostalCode, LastEditedBy)
                      VALUES (:CustomerID, :CustomerName, :BillToCustomerID, :CustomerCategoryID, :DeliveryMethodID, :DeliveryCityID, :PostalCityID, :AccountOpenedDate, :Phonenumber, :DeliveryAddressLine1, :DeliveryAddressLine2, :DeliveryPostalCode, :PostalAddressLine1, :PostalAddressLine2, :PostalPostalCode, :LastEditedBy)');

                        $stmt10->execute(array(
                            ':ID' => $klantID, ':naam' => $fullName, ':email' => $email, ':telefoonnummer' => $telefoonnummer, ':wachtwoord' => $passwordhash));
                        $stmt15->execute(array(
                            //De ingevulde informatie wordt in de database gezet//
                            ':CustomerID' => $klantID, ':CustomerName' => $fullName, ':BillToCustomerID' => $klantID, ':CustomerCategoryID' => 1, ':DeliveryMethodID' => 1, ':DeliveryCityID' => $StadID, ':PostalCityID' => $StadID, ':AccountOpenedDate' => $date, ':Phonenumber' => $telefoonnummer, ':DeliveryAddressLine1' => $straatennummer, ':DeliveryAddressLine2' => $land, ':DeliveryPostalCode' => $postcode, ':PostalAddressLine1' => $straatennummer, ':PostalAddressLine2' => $land, ':PostalPostalCode' => $postcode, ':LastEditedBy' => 1));
                        header('Location: Register.php?action=joined');

                        exit;
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                } else {
                    try {
                        $stmt10 = $conn->prepare('INSERT INTO people (PersonID, FullName, EmailAddress, PhoneNumber, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson, LastEditedBy) VALUES (:ID, :naam, :email, :telefoonnummer, :wachtwoord, 0, 0, 0, 1)');
                        $stmt3 = $conn->prepare('INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, Phonenumber, DeliveryAddressLine1, DeliveryAddressLine2, DeliveryPostalCode, PostalAddressLine1, PostalAddressLine2, PostalPostalCode, LastEditedBy)
                      VALUES (:CustomerID, :CustomerName, :BillToCustomerID, :CustomerCategoryID, :DeliveryMethodID, :DeliveryCityID, :PostalCityID, :AccountOpenedDate, :Phonenumber, :DeliveryAddressLine1, :DeliveryAddressLine2, :DeliveryPostalCode, :PostalAddressLine1, :PostalAddressLine2, :PostalPostalCode, :LastEditedBy)');

                        $stmt10->execute(array(
                            ':ID' => $klantID, ':naam' => $fullName, ':email' => $email, ':telefoonnummer' => $telefoonnummer, ':wachtwoord' => $passwordhash));
                        $stmt3->execute(array(
                            //De ingevulde informatie wordt in de database gezet//
                            ':CustomerID' => $klantID, ':CustomerName' => $fullName, ':BillToCustomerID' => $klantID, ':CustomerCategoryID' => 1, ':DeliveryMethodID' => 1, ':DeliveryCityID' => $CityID2, ':PostalCityID' => $CityID2, ':AccountOpenedDate' => $date, ':Phonenumber' => $telefoonnummer, ':DeliveryAddressLine1' => $straatennummer, ':DeliveryAddressLine2' => $DeliveryAddressLine2, ':DeliveryPostalCode' => $postcode, ':PostalAddressLine1' => $straatennummer, ':PostalAddressLine2' => $land, ':PostalPostalCode' => $postcode, ':LastEditedBy' => 1));
                        header('Location: Register.php?action=joined');

                        exit;
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                }

//
//                try {
//
//                                       //   header('Location: Register.php?action=joined');
//                  
//                   //    header('Location: Register.php?action=joined');
//                    exit;
//                } catch (PDOException $e) {
//                    echo $e->getMessage();
//                }
            } else {
                $errMsg = "Dit Emailadres is al geregistreerd";
            }
        }
            } else { "Passwords do not match";
    }
    }
//Als op de "register' knop wordt gedrukt dan wordt een button zichtbaar die je herleid naar de homepage//
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

<!-- Hier wordt de informatie opgehaald-->
