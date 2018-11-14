<?php
include 'session.php';
include 'connection.php';
$zoek = filter_input(INPUT_GET, "zoek", FILTER_SANITIZE_STRING);

function like_match($pattern, $subject) {
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    return (bool) preg_match("/^{$pattern}$/i", $subject);
}
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
        <?php include 'navbar.php'; ?>

        <div class="container">

            <?php
            if (isset($_GET['zoekKnop'])) {
                try {
                    $search = $_GET['zoek'];
                    $query = $conn->query("SELECT * FROM stockitems WHERE StockItemName LIKE '%" . $search . "%' OR SearchDetails LIKE '%" . $search . "%'");
                    $count = $query->rowCount();
                    $artikelNaamTweedeKeer = '';
                    while ($row = $query->fetch()) {
                        $artikelNaam = $row["StockItemName"];
                        $artikelID = $row["StockItemID"];
                        $artikelPrijs = $row["RecommendedRetailPrice"];
                        $artikelMaat = $row["Size"];
                        $Small = like_match('%S', $artikelMaat);
                        $Medium = like_match('M', $artikelMaat);
                        $Large = like_match('%L', $artikelMaat);
                        if ($Small OR $Medium OR $Large) {
                            $artikelNaam = str_replace(") " . $artikelMaat, '', $artikelNaam);
                            $artikelNaam = $artikelNaam . ")";
                            if ($artikelNaam != $artikelNaamTweedeKeer) {
                                $artikelNaamTweedeKeer = $artikelNaam;
                            } else {
                                goto a;
                            }
                        }
                        if (isset($artikelID)) {
                            ?>

                            <div class="row">
                                <div class="col-lg-4">
                                    <?php $fotoID = rand(1, 18); ?>
                                    <img src="artikelFoto/<?php print($fotoID); ?>.jpg" class="artikelImg">
                                </div>
                                <div class="col-lg-4">
                                    <a href="artikel.php?artikelid=<?php print($artikelID."&maatselected=FALSE"); ?>"><h5><?php print($artikelNaam); ?></h5></a>
                                </div>
                                <div class="col-lg-4">
                                    <?php print("<h3>€" . $artikelPrijs . "</h3>"); ?>
                                </div>
                                <hr style="width:75%">
                            </div> <?php
                            a:
                        }
                    }
                    if ($count == 0) {
                        echo 'Geen zoekresultaten gevonden voor: \'' . $zoek . "'";
                    }
                } catch (PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
            }
            ?>
        </div>





        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>
