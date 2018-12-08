<?php
include 'session.php';
include 'connection.php';
include 'functions.php';
//Hier bevindt zich de belangrijste core van onze website: Sessies, de connectie met de database, en functies
if(!isset($_SESSION["ID"])){
header("Location: index.php");
}
$checkAdmin = $conn->prepare("Select * FROM people WHERE  PersonID = :PersonID");
$checkAdmin->execute(array(":PersonID"=>$_SESSION["ID"]));
$checkAdminID = 0;
while ($ID = $checkAdmin->fetch()) {
$checkAdminID += $ID["IsSystemUser"];
//echo $ID["IsSystemUser"];
}

if($checkAdminID == 1){

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <title></title>

    </head>
    <body>
        
        <!-- Hier wordt de navbar opgevraagd -->
        <?php include 'navbar.php';
		
       ?>
        <div class="container">
	<table class="table">
	  <thead>
		<tr>
			      <th scope="col">Artikelnaam</th>
				<th scope="col">Voorraad</th>
		</tr>
		</thead>
		<tbody>

	<?php 
	$voorraad = $conn->query("SELECT *  FROM stockitemholdings JOIN stockitems ON stockitemholdings.StockItemID = stockitems.StockItemID ORDER BY StockItemName");
	while ($aantalVoorraad = $voorraad->fetch()) {
	?>
	<tr>
	<td><?php echo $aantalVoorraad["StockItemName"]; ?></td>
	<td><?php echo $aantalVoorraad["QuantityOnHand"]; ?> </td
	</tr>
	
	
	<?php } ?>
	</tbody>
	</table>
	
            </div>    


            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<?php } else { header("Location: index.php"); } ?>
    </body>
</html>
