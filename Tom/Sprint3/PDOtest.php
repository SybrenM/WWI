<?php

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
$string = "OverLord";
function splitAtUpperCase($string){
    return preg_replace('/([a-z0-9])?([A-Z])/','$1 $2',$string);
}

$string = 'setIfUnmodifiedSince';
echo splitAtUpperCase($string);

?>