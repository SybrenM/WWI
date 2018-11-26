<?php
include 'session.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'connection.php';
        ?>
        <meta charset="UTF-8">
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
            <a class="navbar-brand" href="#">WideWorldImporters</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
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
      
    <button type="button" class="btn btn-verder btn-lg">  <a href="index.php">Verder Winkelen</a></button>
    <button type="button" class="btn btn-verder btn-lg">  <a href="afrekenen.php">Afrekenen</a></button>  
        </div>
    </body>
</html>
