<?php
session_start();
//$artikelID = filter_input(INPUT_GET, "artikelid", FILTER_SANITIZE_STRING);
include 'connection.php';
$number = filter_input(INPUT_GET, "number", FILTER_SANITIZE_STRING);

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
                    </ul>
                </div>
            </nav>
	    
	                <div class="container">

	    <h1> Winkelmand </h1>
    <?php
if(empty($_SESSION['winkelwagen'])){
	$_SESSION['winkelwagen'] = array();
	}
    if(empty($_SESSION['aantal'])){
	$_SESSION['aantal'] = array();
	}
	if(isset($_GET['artikelid']) && isset($_GET['number'])){
array_push($_SESSION['winkelwagen'], $_GET['artikelid']);
array_push($_SESSION['aantal'], $_GET['number']);
   }
   
   foreach($_SESSION['winkelwagen'] as $key => $value){
	foreach($_SESSION['aantal'] as $key1 => $value1){
	if($key == $key1){
	$counter = $key;
	}
	}
}
   if(!empty($_SESSION['winkelwagen'])){
$selectProducts = implode(',', $_SESSION['winkelwagen']);
    $row = $conn->query("SELECT * FROM stockitems SI JOIN suppliers S on SI.supplierID = S.supplierID WHERE SI.stockitemid IN (".$selectProducts.")");
    $i = 0;
    $totalePrijs = 0;
    while ($artikel = $row->fetch(PDO::FETCH_ASSOC)) {
        $artikelNaam = $artikel["StockItemName"];
        $artikelID = $artikel["StockItemID"];
        $artikelPrijs = $artikel["RecommendedRetailPrice"];
	if(isset($_SESSION['aantal'][$i])){
	$totalePrijs += $artikelPrijs *  $_SESSION['aantal'][$i];
	}
	        ?>


                <div class="row">
                    <div class="col-lg-4">
                        Product: <?php print($artikelNaam); ?> 
		</div>
		<div class="col-lg-2">
                        <div>Prijs: <?php print("€" . $artikelPrijs); ?> </div>
                    </div>
		<div class="col-lg-3">
		    <div> Aantal: <?php
if(isset($_SESSION['aantal'][$i])){		    echo $_SESSION['aantal'][$i];} ?></div>
                     </div>
		<div class="col-lg-3">
		<a href="verwijderArtikel.php?id=<?php echo $artikelID ?>&aantal=<?php echo $i; ?>">Verwijder</a>
		</div>
		</div>

            <?php $i++;} 
	    ?>
	    <div class="row">
		<div class="col-lg-6">
			<div> Totale prijs: <?php echo "€".number_format($totalePrijs, 2); }?> </div>
		</div>
		</div>
	    </div>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>