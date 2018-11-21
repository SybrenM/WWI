 <?php
                $query = $conn->query("SELECT * FROM stockitems SI JOIN stockitemstockgroups SISG on SI.StockItemID = SISG.StockItemID WHERE SISG.StockgroupID = " . $categorie);
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
                    $LengteEen = like_match('%.%m', $artikelMaat);
                    $LengteTwee = like_match('%0m', $artikelMaat);
                    $LengteDrie = like_match('%x%m', $artikelMaat);                    
                    //Hier wordt gekeken of er tussen de artikelen iets zit met een maatje S, M of L. Hiervan wordt er maar één weergegeven tussen de resultaten. De maat kan dan pas op de artikelpagina zelf worden gekozen.
                    if ($LengteEen OR $LengteTwee OR $LengteDrie) {
                        $artikelNaam = str_replace($artikelMaat, '', $artikelNaam);
                        $artikelNaam = $artikelNaam;
                        if ($artikelNaam != $artikelNaamTweedeKeer) {
                            $artikelNaamTweedeKeer = $artikelNaam;
                        } else {
                            goto a;
                        }
                    }
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
                        <!-- Hier komt een rij voor één artikel met, van links naar rechts: foto, naam, prijs -->
                        <a href="artikel.php?artikelid=<?php print($artikelID."&maatselected=FALSE"); ?>" class="artikelTekst">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <?php $fotoID = rand(1, 18); ?>
                                <img src="artikelFoto/<?php print($fotoID); ?>.jpg"  class="artikelImg">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <h5><?php print($artikelNaam); ?></h5>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <?php print("<h3 class='artikelPrijs'>€" . $artikelPrijs . "</h3>"); ?>
                            </div>
                            <hr style="width:80%">
                        </div>
                        </a>
                        <?php 
                        a:
                        } else {
                        print("Niks gevonden.");
                    }
                }
                        ?>
                  