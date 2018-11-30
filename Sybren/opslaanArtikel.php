<?php
include 'connection.php';
session_start();
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
$aantal = filter_input(INPUT_GET, "aantal", FILTER_SANITIZE_STRING);

    echo 'sdf';

$row = $conn->query("SELECT * FROM stockitems WHERE StockItemID = ".$id);
    while ($artikel = $row->fetch()) {
    echo 'sdf';
    }