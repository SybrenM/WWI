<?php
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
$aantal = filter_input(INPUT_GET, "aantal", FILTER_SANITIZE_STRING);

session_start();
include 'connection.php';
$row = $conn->query("SELECT * FROM stockitems WHERE StockItemID = ".$id);
    while ($artikel = $row->fetch()) {
	foreach($_SESSION['winkelwagen'] as $key => $value){
					if($value == $artikel["StockItemID"]){

			$keys = array_keys($_SESSION['winkelwagen']);
				if($keys[$aantal] == $key){
				echo "Aantal :". $_SESSION['aantal'][$key];
				unset ( $_SESSION['aantal'][$key]);
			unset($_SESSION['winkelwagen'][$key]);
				header("Location: winkelwagen.php");

			}
			
		//}
    }

}

}


