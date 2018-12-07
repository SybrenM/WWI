<?php
if ($artikelWinkelmand = 'winkelmand') {
// Als session leeg is, maak een nieuwe array
if (empty($_SESSION['winkelwagen'])) {
    $_SESSION['winkelwagen'] = array();
}
if (empty($_SESSION['aantal'])) {
    $_SESSION['aantal'] = array();
}


// Als artikel  aantal groter dan 1 is, pushen we het aantal en artikel in een session array
if (isset($_POST['artikelid']) && isset($_POST['number']) && $_POST['number'] >= 1) {
    array_push($_SESSION['winkelwagen'], $_POST['artikelid']);
    array_push($_SESSION['aantal'], $_POST['number']);
    asort($_SESSION['winkelwagen']);
} elseif (isset($_POST['number']) && $_POST['number'] < 1) {
    echo 'Aantal moet groter dan 0 zijn';
}


if (!empty($_SESSION['winkelwagen'])) {
    $selectProducts = implode(',', $_SESSION['winkelwagen']);
    $row = $conn->query("SELECT * FROM stockitems SI JOIN suppliers S on SI.supplierID = S.supplierID WHERE SI.stockitemid IN (" . $selectProducts . ")");
    $i = 0;   //opteller key van Session array
    $totalePrijs = 0;

    $keys = array_keys($_SESSION['winkelwagen']);
    asort($_SESSION['winkelwagen']); //waardes sorteren in Session array "winkelwagen"

    while ($artikel = $row->fetch(PDO::FETCH_ASSOC)) {
        $artikelNaam = $artikel["StockItemName"];
        $artikelID = $artikel["StockItemID"];
        $artikelPrijs = $artikel["RecommendedRetailPrice"];

        foreach ($_SESSION['winkelwagen'] as $key => $value) {
//Als de session Key van winkelwagen met de opteller '$i' gelijk is aan de session Key van winkelwagen
            if ($keys[$i] == $key) {
                $totalePrijs += $artikelPrijs * $_SESSION['aantal'][$key];  //totale prijs berekening
            }
        }
        ?>


        <div class="row">
            <div class="col-lg-4">
                Product: <?php
        print $artikelNaam;
        ?> 
            </div>
            <div class="col-lg-2">
                <div>Prijs: <?php print("€" . $artikelPrijs); ?> </div>
            </div>
            <div class="col-lg-3">
                <div> Aantal: <?php
        foreach ($_SESSION['winkelwagen'] as $key => $value) {
//Als de session Key van winkelwagen met de opteller '$i' gelijk is aan de session Key van winkelwagen
            if ($keys[$i] == $key) {
                echo ($_SESSION['aantal'][$key]);
            }
        }
        ?></div>
            </div>
            <div class="col-lg-3">
                <a href="verwijderArtikel.php?id=<?php echo $artikelID ?>&aantal=<?php echo $i; ?>">Verwijder</a>
            </div>
        </div>

        <?php
        $i++;
    }   //sluiting while loop
}
?>
<?php if (isset($totalePrijs)) { ?>
    <div class="row">
        <div class="col-lg-6">
            <div> Totale prijs: <?php echo "€" . number_format($totalePrijs, 2); ?> </div>
        </div>
    </div>
<?php } ?>
</div>

<form action="sessiondestroy.php" method="post">
    <input type="submit" value="verwijder items" name="items">
</form>

<?php } else {
    if ($_POST['number'] < 1) {
    echo 'Aantal moet groter dan 0 zijn';
}
    
}
?>