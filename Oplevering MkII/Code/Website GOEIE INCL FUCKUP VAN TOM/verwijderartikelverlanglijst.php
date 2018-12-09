<?php
//Hier wordt het artikel uit het verlanglijstje verwijdert
session_start();
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);

include 'connection.php';
$row = $conn->query("SELECT * FROM stockitems WHERE StockItemID = " . $id);
while ($artikel = $row->fetch()) {
    foreach ($_SESSION['verlanglijstje'] as $key => $value) {
        if ($value == $artikel["StockItemID"]) {
            $keys = array_keys($_SESSION['verlanglijstje']);
            unset($_SESSION['verlanglijstje'][$key]);
            header("Location: verlanglijstje.php");
        }
    }
}



