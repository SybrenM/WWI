<?php
include 'session.php';
include 'connection.php';
//$artikelID = 1;
//$stmt = $conn->query('SELECT * FROM review WHERE StockItemID = :fag');
//$stmt->execute(array(
//    ':fag' => $artikelID
//));
//while ($data = $stmt->fetch()) {
//    $PersonID = $data['PersonID'];
//    $review = $data['review'];
//    $reviewname = $data['reviewname'];
//    $rating = $data['rating'];
//}
//echo $rating;

//
//$email = 'lord@overlord.com';
//$stmt = $conn->prepare('SELECT * FROM people WHERE EmailAddress = :email');
//$stmt->execute(array(
//    //Hier worden de eerder ingevulden variabelen ge-assigend aan de rijen in de database// 
//    ':email' => $email
//));
//$data = $stmt->fetch();
//$FullName = $data['FullName'];
//echo $FullName;
//echo "test";
//

//$spatie = "Spatie" . " " . "Spatie";
//echo $spatie;
//$plaats = 'George';
// $stmt3 = $conn->prepare('SELECT CityID FROM cities WHERE CityName = :plaats LIMIT 1');
//        $stmt3->execute(array(':plaats' => $plaats));
//        $CityID2 = 0;
//        while ($query = $stmt3->fetch()) {
//            $CityID2 += $query['CityID'];
//            echo $query["CityID"];
//        }
//$_SESSION['email'] = "2";
//    if(isset($_SESSION['email'])) {
//                        $stmt = $conn->prepare('SELECT FullName FROM people WHERE EmailAddress = :EmailAddress');
//                           $stmt->execute(array(
//                              ':EmailAddress' => $_SESSION['email'] 
//                           ));
//                           while($data = $stmt->fetch()) {
//                               $klantNaam = $data['FullName'];
//                           }
//                           echo $klantNaam;
//    }

//$stmtKlantInfo = $conn->prepare('SELECT * FROM customers WHERE CustomerID = :CustomerID');
//$stmtKlantInfo->execute(array(':CustomerID' => $_SESSION['ID']));
//while ($data = $stmtKlantInfo->fetch()) {
//    $PersonID = $data['CustomerID'];
//    $FullNameUntrimmed = $data['CustomerName'];
//
//echo $FullNameUntrimmed;  }
$straat = 'Dorpstraat 15';
 echo preg_replace('/\D/','',$straat);

?>