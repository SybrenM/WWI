<?php
//Hier bevindt zich de belangrijste core van onze website: Sessies, de connectie met de database, en functies
include 'session.php';
include 'connection.php';
include 'functions.php';
$vergelijkID = $_GET['vergelijk']
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="categorie-style.css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <title></title>

    </head>
    <body>

        <!-- Hier wordt de navbar opgevraagd -->
        <?php
        include 'navbar.php';

        $aantalArtikelen = count($vergelijkID);
        if ($aantalArtikelen > 5) {
            if ($aantalArtikelen == 6) {
                $marginLeft = "200px";
            } else {
                $marginLeft = "0px";
            }
        } else {
            $marginLeft = "";
        }
        ?>       
        <style>
            .container {
                margin-left: <?php print($marginLeft); ?>
            }
        </style>
        <div class="container">

            <?php
            foreach ($vergelijkID as $vergelijking => $artikelID) {
                $query = $conn->prepare("SELECT * FROM stockitems SI LEFT JOIN colors C on SI.ColorID = C.colorID WHERE stockItemID = " . $artikelID);
                $query->execute();
                $result = $query->fetchAll(\PDO::FETCH_ASSOC);
                print_r($result);
                print("<BR><BR>");
            }
            foreach ($result as $a => $b) {
                print_r($result);
                echo $result[$a]["ColorName"];
                
            }
            ?>
        </div>    
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>
