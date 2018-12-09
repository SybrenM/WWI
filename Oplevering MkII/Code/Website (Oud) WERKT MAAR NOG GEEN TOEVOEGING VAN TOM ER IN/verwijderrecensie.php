<?php

session_start();
include 'connection.php';
echo 'success';


$artikelID = filter_input(INPUT_GET, "artikelID", FILTER_SANITIZE_STRING);
$reviewid = filter_input(INPUT_GET, "reviewid", FILTER_SANITIZE_STRING);
$query2 = $conn->prepare('DELETE FROM review WHERE reviewid = :reviewid');
$query2->execute(array(':reviewid' => $reviewid));



header('Location: artikel.php?artikelid=' . $artikelID);

