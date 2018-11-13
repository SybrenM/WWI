<?php
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
$aantal = filter_input(INPUT_GET, "aantal", FILTER_SANITIZE_STRING);

session_start();
include 'connection.php';
$row = $conn->query("SELECT * FROM stockitems WHERE StockItemID = ".$id);
    while ($artikel = $row->fetch()) {
	foreach($_SESSION['winkelwagen'] as $key => $value){
					if($value == $artikel["StockItemID"]){

		//	foreach($_SESSION['aantal'] as $key1 => $value1){
			$keys = array_keys($_SESSION['winkelwagen']);
			
			//echo $keys[$aantal]. ' ';
			//echo $key. ' ';
				if($keys[$aantal] == $key){
				//echo $keys[$aantal];
							//echo $key;

				echo "Aantal :". $_SESSION['aantal'][$key];
				unset ( $_SESSION['aantal'][$key]);

			unset($_SESSION['winkelwagen'][$key]);
				echo "Winkelwagen :". $_SESSION['winkelwagen'][$key];

				//$_SESSION['aantal'] = array_values($_SESSION['aantal']);
				//$_SESSION['winkelwagen'] = array_values($_SESSION['winkelwagen']);

				header("Location: winkelwagen.php");

			}
			
		//}
    }

}

}


