<?php
include 'session.php';
include 'connection.php';
include 'functions.php';
$number = filter_input(INPUT_GET, "number", FILTER_SANITIZE_STRING);
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
        <title>Verlanglijsjte</title>
    </head>
    <body><?php include 'navbar.php'; ?>
        <div class="container">
            <h1> Verlanglijstje </h1>
            <?php
            // Als session leeg is, maak een nieuwe array
            if (empty($_SESSION['verlanglijstje'])) {
                $_SESSION['verlanglijstje'] = array();
            }
            if (empty($_SESSION['aantal'])) {
                $_SESSION['aantal'] = array();
            }

            // Als artikel  aantal groter dan 1 is, pushen we het aantal en artikel in een session array
            if (isset($_POST['artikelid']) && isset($_POST['number']) && $_POST['number'] >= 1) {
                array_push($_SESSION['verlanglijstje'], $_POST['artikelid']);
                array_push($_SESSION['aantal'], $_POST['number']);
                asort($_SESSION['verlanglijstje']);
            } elseif (isset($_POST['number']) && $_POST['number'] < 1) {
                echo 'Aantal moet groter dan 0 zijn';
            }

            if (!empty($_SESSION['verlanglijstje'])) {
                $selectProducts = implode(',', $_SESSION['verlanglijstje']);
                $row = $conn->query("SELECT * FROM stockitems SI JOIN suppliers S on SI.supplierID = S.supplierID WHERE SI.stockitemid IN (" . $selectProducts . ")");
                $i = 0;   //opteller key van Session array
                $totalePrijs = 0;

                $keys = array_keys($_SESSION['verlanglijstje']);
                asort($_SESSION['verlanglijstje']); //waardes sorteren in Session array "winkelwagen"

                while ($artikel = $row->fetch(PDO::FETCH_ASSOC)) {
                    $artikelNaam = $artikel["StockItemName"];
                    $artikelID = $artikel["StockItemID"];
                    $artikelPrijs = $artikel["RecommendedRetailPrice"];

                    foreach ($_SESSION['verlanglijstje'] as $key => $value) {
//Als de session Key van winkelwagen met de opteller '$i' gelijk is aan de session Key van winkelwagen
                        if ($keys[$i] == $key) {
                            $totalePrijs += $artikelPrijs * $_SESSION['aantal'][$key];  //totale prijs berekening
                        }
                    }
                    ?>

                    <div class="row">
                        <div class="col-lg-6">
                            Product: <?php
                            print $artikelNaam;
                            ?> 
                        </div>
                        <div class="col-lg-3">
                            <div>Prijs: <?php print("€" . $artikelPrijs); ?> </div>
                        </div>
                        <div class="col-lg-3">
                            <a href="verwijderartikelverlanglijst.php?id=<?php echo $artikelID ?>">Verwijder</a>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
        <div class="row">
            <div class="offset-lg-8">
                <button type="button" class="btn btn-verder btn-lg">  <a class="winkelwagenlinkjes" href="index.php">Verder Winkelen</a></button>

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