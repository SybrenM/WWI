<?php
include 'session.php';
$categorie = filter_input(INPUT_GET, "categorie", FILTER_SANITIZE_STRING);

?>

<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'connection.php';
        ?>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="categorie-style.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <title></title>

    </head>
    <body><?php
        $row = $conn->query("SELECT count(*) FROM stockgroups");
        while ($artikel = $row->fetch()) {
            $max = $artikel["count(*)"];
        }
        ?>
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
                    <form class="form-inline" action="zoek.php">
                        <input class="form-control mr-sm-2" type="search" placeholder="Zoek artikel..." aria-label="Search" name="zoek">
                        <button class="btn btn-primary" type="submit" name="zoekKnop">Zoeken</button>


                    </form>
                </ul>
            </div>
        </nav>


        <div class="container">
            <h1>
                <?php
                $row = $conn->query("SELECT * FROM stockitems SI JOIN stockitemstockgroups SISG on SI.StockItemID = SISG.StockItemID WHERE SISG.StockgroupID = " . $categorie);
                while ($artikel = $row->fetch()) {
                    $artikelNaam = $artikel["StockItemName"];
                    $artikelID = $artikel["StockItemID"];
                    $artikelPrijs = $artikel["RecommendedRetailPrice"];

                    if (isset($artikelID)) {
                        ?>         
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                            <?php $fotoID = rand(1, 18); ?>
                                <img src="artikelFoto/<?php print($fotoID); ?>.jpg"  class="artikelImg">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <a href="artikel.php?artikelid=<?php print($artikelID); ?>" class="artikelTekst"><h5><?php print($artikelNaam); ?></h5></a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <?php print("<h3 class='artikelPrijs'>€" . $artikelPrijs . "</h3>"); ?>
                            </div>
                            <hr style="width:75%">
                        </div>

                        <?php
                    } else {
                        print("Niks gevonden.");
                    }
                }
                ?>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>
