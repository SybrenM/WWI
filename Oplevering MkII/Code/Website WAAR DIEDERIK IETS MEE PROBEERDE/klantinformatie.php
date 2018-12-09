<?php

include 'session.php';
include 'connection.php';
include 'functions.php';

$email = $_SESSION['email'];
$stmt = $conn->prepare('SELECT * FROM customers WHERE CustomerID = :CustomerID');
$stmt->execute(array(':CustomerID' => $_SESSION['ID']));
while ($data = $stmt->fetch()) {
    $PersonID = $data['CustomerID'];
    $FullNameUntrimmed = $data['CustomerName'];
}
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
     <!--   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <title>Klantinformatie</title> 
    <body>
<?php
include 'navbar.php';
$FullName = preg_replace('/[0-9]+/', '', (preg_replace('/([a-z0-9])?([A-Z])/', '$1 $2', $FullNameUntrimmed)));
?>
        <div class="container">
            <h1> Klant informatie </h1><br>
            <b> Afleverinformatie: </b>
            Welkom <?php echo " " . $FullName  ?>
	    
	    <?php 
$selectInfo = $conn->prepare("SELECT * FROM customers JOIN cities on DeliveryCityID = CityID WHERE CustomerID = :CustomerID ");
$selectInfo->execute(array(':CustomerID' => $_SESSION["ID"]));
while($infoCustomer = $selectInfo->fetch()){
?>
<br> 
<?php 
echo "Telefoonnummer: ".$infoCustomer["PhoneNumber"];
?>
<br>
<?php
echo "Adres: ".$infoCustomer["DeliveryAddressLine1"];
?>
<br>
<?php
echo "Plaats: ".$infoCustomer["CityName"];
?>
<br>
<?php
echo "Postcode: ".$infoCustomer["DeliveryPostalCode"];
?>
<br>
<br>
<br>
<?php
}
?>

<b> Bestellingen: </b><br>

<?php
 $InvoiceExist = $conn->prepare("SELECT COUNT(CustomerID) FROM invoices WHERE CustomerID = :CustomerID");
            $InvoiceExist->execute(array(':CustomerID' => $_SESSION['ID']));
            while ($CountInvoice = $InvoiceExist->fetch()) {
                $InvoiceCount = $CountInvoice["COUNT(CustomerID)"];
            }

            if($InvoiceCount > 0) {
                $InvoiceInfo = $conn->prepare('SELECT * FROM invoices WHERE CustomerID = :CustomerID');
                $InvoiceInfo->execute(array(':CustomerID' => $_SESSION['ID']));
                while ($InfoInvoice = $InvoiceInfo->fetch()) {
                    echo 'Invoice ID: ' . $InfoInvoice['InvoiceID']; ?> <br> <?php
                    echo 'Invoice date: ' . $InfoInvoice['InvoiceDate'];
                }
            } else {
                echo 'Nog geen bestellingen gemaakt';
            }
?>

































































<?php  ?>
    </body>
</head>
<!--Insert Code Here-->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>  