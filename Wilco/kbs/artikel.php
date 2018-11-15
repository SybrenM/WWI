<?php
session_start();
$artikelID = filter_input(INPUT_GET, "artikelid", FILTER_SANITIZE_STRING);
include 'connection.php';

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="artikel-style.css">
        <link rel="stylesheet" type="text/css" href="style.css">

        <style>
            .placeholder{
                max-width: 100%;
            }
            .description, .prijs{
                margin-top: 100px;
            }
        </style>
        <title>Hello, world!</title>
    </head>
    <?php
    $row = $conn->query("SELECT * FROM stockitems SI JOIN suppliers S on SI.supplierID = S.supplierID WHERE SI.stockitemid = " . $artikelID);
    while ($artikel = $row->fetch()) {
        $artikelNaam = $artikel["StockItemName"];
        $artikelID = $artikel["StockItemID"];
        $artikelPrijs = $artikel["RecommendedRetailPrice"];
        $slogan = $artikel["MarketingComments"];
        $derdePartij = $artikel["SupplierName"];
        ?>
        <body>
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="index.php">WideWorldImporters</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Categorieën
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <?php
                                $row = $conn->query("SELECT DISTINCT SG.StockGroupName, SG.StockGroupID FROM stockgroups SG JOIN stockitemstockgroups SISG on SG.StockGroupID = SISG.StockGroupID ORDER BY SG.StockGroupID");
                                while ($artikel = $row->fetch()) {
                                    $categorieID = $artikel["StockGroupID"];
                                    $categorieNaam = $artikel["StockGroupName"];
                                    ?>
                                    <a class="dropdown-item" href="categorie.php<?php print("?categorie=" . $categorieID); ?>"><?php print($categorieNaam); ?></a>
                                <?php } ?>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                         <form class="form-inline" action="zoek.php" method="get">
                        <input class="form-control mr-sm-2" type="search" placeholder="Zoek artikel..." aria-label="Search" name="zoek">
                        <button class="btn btn-primary" type="submit" name="zoekKnop">Zoeken</button>
			</form>
			  <li class="nav-item">
                            <a class="nav-link" href="winkelwagen.php">Winkelmand</a>
                        </li>
                    </ul>
                </div>
            </nav>



            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <img src="artikelFoto/placeholder.jpg" class="artikelImg">
                    </div>
                    <div class="col-lg-6">
                        Product: <?php print($artikelNaam); ?> 
                        <div class="prijs">Prijs: <?php print("€" . $artikelPrijs); ?> </div>
                        <div class="description">Extra informatie: <?php print($slogan); ?> </div>
                    </div>
                </div>

            <?php } print($derdePartij); 
	    
	      if(empty($_SESSION['winkelwagen'])){
	$_SESSION['winkelwagen'] = array();
	?>
	<form action="winkelwagen.php" method="get">
	<input type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
	<input type="number" name="number">
	<input type="submit" value="Aan winkelmand toevoegen">
	</form>
	
	<?php
	}
	
	foreach($_SESSION['winkelwagen'] as $key => $value){
	   if($artikelID == $value){
	   echo ' <br> U heeft dit artikel al in uw winkelwagen staan';
	   $exists = "";
		} 
	   }
	   if(!isset($exists) && !empty($_SESSION['winkelwagen'])) {
	   ?>
	
	<form action="winkelwagen.php" method="get">
	<input type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
	<input type="number" name="number">
	<input type="submit" value="Aan winkelmand toevoegen">
	</form>
	<?php   } ?>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>