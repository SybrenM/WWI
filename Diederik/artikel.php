<?php
include 'session.php';
include 'connection.php';

$artikelID = filter_input(INPUT_GET, "artikelid", FILTER_SANITIZE_STRING);
$maatSelected = filter_input(INPUT_GET, "maatselected", FILTER_SANITIZE_STRING);

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function like_match($pattern, $subject) {
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    return (bool) preg_match("/^{$pattern}$/i", $subject);
}

$sizeProblem = FALSE;
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
    //Hier wordt één specifiek artikel opgezocht met het artikelID die meekomt uit de link
    $query = $conn->query("SELECT * FROM stockitems SI JOIN suppliers S on SI.supplierID = S.supplierID WHERE SI.stockitemid = " . $artikelID);
    $count = $query->rowCount();
    $artikelNaamTweedeKeer = '';
    while ($row = $query->fetch()) {
        $artikelNaam = $row["StockItemName"];
        $artikelID = $row["StockItemID"];
        $artikelPrijs = $row["RecommendedRetailPrice"];
        $artikelMaat = $row["Size"];
        $slogan = $row["MarketingComments"];
        $derdePartij = $row["SupplierName"];
        $Small = like_match('%S', $artikelMaat);
        $Medium = like_match('M', $artikelMaat);
        $Large = like_match('%L', $artikelMaat);
        //Hier wordt gekeken of de maat van het artikel iets in zich heeft dat eindigd op S of L, of dat M is. Als dat zo is, dan wordt het maatprobleem op TRUE gezet.
        if ($Small OR $Medium OR $Large) {
            $artikelDing = str_replace(") " . $artikelMaat, '', $artikelNaam);
            $artikelDing = $artikelDing . ")";
            $sizeProblem = TRUE;
        }
        ?>
        <body>
            <!-- Hier wordt de navbar opgevraagd -->
            <?php include 'navbar.php'; ?>
            <!-- Hier komt de pagina zelf -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <img src="artikelFoto/placeholder.jpg" class="artikelImg">
                    </div>
                    <div class="col-lg-6">
                        Product: <?php
                        //Als het bekende maatprobleem zich afspeelt, en daarbij de maat nog niet geselecteerd is, dan wordt het artikel weergegeven zonder maat. (Er is dus nog geen maat gekozen.)
                        if ($maatSelected == 'FALSE' AND $sizeProblem) {
                            print($artikelDing); //Artikelnaam wordt weergegeven zonder maat er achter. (dus niet zoals dat standaart in de database staat.)
                        } ELSE {
                            print($artikelNaam); //Artikelnaam wordt weergegeven met maat er achter. (Dus zoals dat standaart in de database staat.)
                        }
                        ?>
                        <div class = "prijs">Prijs: <?php print("€" . $artikelPrijs);
                        ?> </div>
                        <?php if ($sizeProblem) { ?> 
                            <div class="dropdown">
                                <button class="btn kleur dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php
                                    if ($maatSelected == "FALSE") {
                                        print("Selecteer uw maat:");
                                    } else {
                                        print($artikelMaat);
                                    }
                                    ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <?php
                                    $row = $conn->query("SELECT * FROM stockitems WHERE stockItemName LIKE '%" . $artikelDing . "%'");
                                    while ($artikel = $row->fetch()) {
                                        $sizeNaam = $artikel["Size"];
                                        $row2 = $conn->query("SELECT * FROM stockitems WHERE stockItemName LIKE '%" . $artikelDing . "%' AND Size = '" . $sizeNaam . "'");
                                        while ($artikel2 = $row2->fetch()) {
                                            $artikelIDLink = $artikel2["StockItemID"];
                                            ?>
                                            <a class = "dropdown-item" href = "artikel.php?artikelid=<?php print($artikelIDLink . "&maatselected=TRUE"); ?>"><?php print($sizeNaam);
                                            ?></a>
                                            <?php
                                        }
                                    }
                                    ?> 
                                </div>
                            </div>
                        <?php } ?>
                        <div class="description">Extra informatie: <?php print($slogan); ?> </div>
                    </div>
                </div>

                <?php
            } print($derdePartij);
            if ($maatSelected == 'TRUE' OR ! $sizeProblem) {
                if (empty($_SESSION['winkelwagen'])) {
                    $_SESSION['winkelwagen'] = array();
                    ?>
                    <form action="winkelwagen.php" method="get">
                        <input type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
                        <input type="number" name="number">
                        <input type="submit" value="Aan winkelmand toevoegen">
                    </form>

                    <?php
                }

                foreach ($_SESSION['winkelwagen'] as $key => $value) {
                    if ($artikelID == $value) {
                        echo ' <br> U heeft dit artikel al in uw winkelwagen staan';
                        $exists = "";
                    }
                }
                if (!isset($exists) && !empty($_SESSION['winkelwagen'])) {
                    ?>

                    <form action="winkelwagen.php" method="get">
                        <input type="hidden" value="<?php echo $artikelID; ?>" name="artikelid">
                        <input type="number" name="number">
                        <input type="submit" value="Aan winkelmand toevoegen">
                    </form>
                    <?php
                }
            } else {
                print("<BR><BR>Kies eerst een maat.");
            }
            ?>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>