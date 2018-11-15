<?php
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
$aantal = filter_input(INPUT_GET, "aantal", FILTER_SANITIZE_STRING);

session_start();
include 'connection.php';
$row = $conn->query("SELECT * FROM stockitems WHERE StockItemID = ".$id);
    while ($artikel = $row->fetch()) {
	foreach($_SESSION['winkelwagen'] as $key => $value){
		if($value == $artikel["StockItemID"]){

			unset($_SESSION['winkelwagen'][$key]);
			foreach($_SESSION['aantal'] as $key1 => $value1){
				if($key1 == $aantal){
				unset ( $_SESSION['aantal'][$aantal]);
				$_SESSION['aantal'] = array_values($_SESSION['aantal']);
				header("Location: winkelwagen.php");

			}
			
		}
    }

}

}


