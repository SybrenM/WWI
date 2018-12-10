<?php
session_start();
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
$aantal = filter_input(INPUT_GET, "aantal", FILTER_SANITIZE_STRING);


include 'connection.php';
//Query controleert de artikelitem die verwijdert moet worden,
$row = $conn->query("SELECT * FROM stockitems WHERE StockItemID = ".$id);
    while ($artikel = $row->fetch()) {
	foreach($_SESSION['winkelwagen'] as $key => $value){
		//Als de stockitemid van winkelwagen hetzelfde is als stockitemid uit sessie winkelwagen

					if($value == $artikel["StockItemID"]){
	//Zet alle keys van sessie array "winkelwagen" in variable $keys

			$keys = array_keys($_SESSION['winkelwagen']);
				//Als de key van sessie array "winkelwagen" met als waarde variabel $i van winkelwagen.php overeen komt met key van sessie array "winkelwagen"

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


