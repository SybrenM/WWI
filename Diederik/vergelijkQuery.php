<?php

foreach ($vergelijkID as $vergelijking => $artikelID) {
    $query = $conn->query("SELECT * FROM stockitems SI LEFT JOIN colors C on SI.ColorID = C.colorID WHERE stockItemID = " . $artikelID);
    while ($row = $query->fetch()) {
        $artikelNaam = $row["StockItemName"]; //De naam van het artikel
        $artikelID = $row["StockItemID"]; //Het ID van het artikel
        $artikelPrijs = $row["RecommendedRetailPrice"]; //De prijs van het artikel
        $artikelMaat = $row["Size"]; //De eventuele maat van het artikel (is soms NULL)
        $artikelKleur = $row["ColorName"]; //De kleur van het artikel (is soms NULL)
?>