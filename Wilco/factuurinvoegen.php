<?php 
session_start();
include 'connection.php';
$number = filter_input(INPUT_GET, "number", FILTER_SANITIZE_STRING);
// This function will run within each post array including multi-dimensional arrays 
function ExtendedAddslash(&$params)
{ 
        foreach ($params as &$var) {
            // check if $var is an array. If yes, it will start another ExtendedAddslash() function to loop to each key inside.
            is_array($var) ? ExtendedAddslash($var) : $var=addslashes($var);
        }
}

     // Initialize ExtendedAddslash() function for every $_POST variable
    ExtendedAddslash($_POST);      



// Step 2:  I identified each POST data with a variable.  You will also notice that I merged the two land number POST variables in to one (I just liked it that way :) ):

$factuurID = $_POST['factuurID']; 
$klantnaam = $_POST['klantnaam'];
$address = $_POST['address'];
$postcode = $_POST['postcode'];
$stad = $_POST['stad'];
$land = $_POST['land'];



//Step 4: To check if an insert or an update should be done, a search should be initialized if the submission ID exists and then set a condition that if the submission ID exists, an update should be initialized using that submission ID.  And if there is no submission ID matching, then a new insert is going to be made:


// search submission ID

$query = "SELECT * FROM `factuur` WHERE `factuurID` = '$factuurID'";
$sqlsearch = mysql_query($query);
$resultcount = mysql_numrows($sqlsearch);

if ($resultcount > 0) {
 
    mysql_query("UPDATE `factuur` SET 
                                `postcode` = '$postcode',
                                `stad` = '$stad',
                                `land` = '$land'    
                             WHERE `factuurID` = '$factuurID'") 
     or die(mysql_error());
    
} else {

    mysql_query("INSERT INTO `factuur` (factuurID, klantnaam, address, postcode, stad, land) 
                               VALUES ('$factuurID', '$klantnaam', '$address', '$postcode', '$stad', '$land') ") 
    or die(mysql_error());  

}


// You're done.  Name the script as your thank you php file and upload it to match the exact detail that you have set in your Custom Thank You URL

//Here's the complete script:



// Initialize ExtendedAddslash() function for every $_POST variable
ExtendedAddslash($_POST);      

$factuurID = $_POST['factuurID']; 
$klantnaam = $_POST['klantnaam'];
$address = $_POST['address'];
$postcode = $_POST['postcode'];
$stad = $_POST['stad'];
$land = $_POST['land'];


// search submission ID

$query = "SELECT * FROM `factuur` WHERE `factuurID` = '$factuurID'";
$sqlsearch = mysql_query($query);
$resultcount = mysql_numrows($sqlsearch);

if ($resultcount > 0) {
 
    mysql_query("UPDATE `factuur` SET 
                                `postcode` = '$postcode',
                                `stad` = '$stad',
                                `land` = '$land',    
                             WHERE `factuurID` = '$factuurID'") 
     or die(mysql_error());
    
} else {

    mysql_query("INSERT INTO `factuur` (factuurID, klantnaam, IP, postcode, stad, land) 
                               VALUES ('$factuurID', '$klantnaam', '$address', 
                                                 '$postcode', '$stad', '$land') ") 
    or die(mysql_error());  

}
?>
