
<nav class="navbar navbar-expand-lg navbar-light"
     <!-- Hier is de home-knop -->
     <a class="navbar-brand" href="index.php">WideWorldImporters</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <!--<li class="nav-item active">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>-->
            <!-- Hier is de dropdown menu van de categoriën -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Categorieën
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <!-- Dit stukje code haalt alle catergoriën uit de database waar één of meer StockItems aan verbonden zijn -->
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
            <!-- Hier is de knop naar de Contact-pagina -->
            <!--<li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </li>-->
            <!-- Hier zit de zoekfunctie -->
            <form class="form-inline" action="zoek.php">
                <input class="form-control mr-sm-2" type="search" placeholder="<?php
                if (isset($_GET["zoek"])) {
                    print($_GET["zoek"]);
                } else {
                    print("Zoek artikel...");
                }
                ?>" aria-label="Search" name="zoek">
                <button class="btn btn-primary" type="submit">Zoeken</button>


            </form>
            <li class="nav-item">
                <a class="nav-link" href="winkelwagen.php">Winkelmand</a>
            </li>
<?php if (!isset($_SESSION['email'])) { ?>

                <li class="nav-item">
                    <a class="nav-link" href="LoginMain.php">Inloggen</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="Register.php">Registreren</a>
                </li>
<?php } ?>
<?php if (isset($_SESSION['email'])) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Uitloggen</a>
                </li>
<?php } ?>
        </ul>
    </div>
</nav>
