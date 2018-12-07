<!DOCTYPE hmtl>

<?php
//Dit is niet de definitieve versie
include 'session.php';
include 'connection.php';
include 'functions.php';
$errMsg = 'Help';


#echo "Success";
?>


<html>
    <head>
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
                        <li class="nav-item">

                            <?php
                            //Controleerd of de gebruiker is ingelogd. als dit niet het geval is wordt de Login knop weergeven//
                            if (!isset($_SESSION['email'])) {
                                ?>  <a class="nav-link" href="LoginMain.php">Login</a> <?php
                            }
                            ?>
                            <?php if (!isset($_SESSION['email'])) {
                                ?> <a class="nav-link" href="Register.php">Register</a> <?php
                            }
                            ?>

                            <?php
                            //Controleerd of de gebruiker is ingelogd. als dit het geval is dan wordt de Logout knop weergeven//
                            if (!empty($_SESSION['email'])) {
                                ?>  <a class="nav-link" href="logout.php">Logout</a> <?php
                            }
                            ?>
                            <?php
                            //Controleerd of de gebruiker is ingelogd. als dit niet het geval is wordt de Login knop weergeven//
                            if (isset($_SESSION['email'])) {
                                ?> <a class="nav-link" href="customerinfo.php">Klantinformatie</a> <?php
                            }
                            ?>



                    </form>
                    </li>

                </ul>
                </form>
                </ul>
            </div>
        </nav>   
        <div class="container">

            <div style="background-color:#006D9C; color:#FFFFFF; padding:10px;"><b>Login</b></div>
            <div style="margin: 15px">
                <form action="" method="post">
                    <input type="text" name="voornaam" value="<?php
                            if (isset($_POST['voornaam'])) {
                                echo $_POST['voornaam'];
                            }
                            ?>" placeholder="Achternaam" autocomplete="off" class="box"/><br /><br />
                    <input type="text" name="achternaam" value="<?php
                           if (isset($_POST['achternaam'])) {
                               echo $_POST['achternaam'];
                           }
                           ?>" placeholder="Wachtwoord" autocomplete="off" class="box"/><br /><br />
                    <input type="password" name="wachtwoord" value="<?php
                           if (isset($_POST['wachtwoord'])) {
                               echo $_POST['wachtwoord'];
                           }
                           ?>" placeholder=Wachtwoord autocomplete="off" class="box" /><br/><br />
                    <input type="submit" name='login' value="Login" class='submit'/><br />
                </form>
            </div>
<?php
$voornaam = $_POST['voornaam'];
$achternaam = $_POST['achternaam'];
$wachtwoord = $_POST['wachtwoord'];
echo $wachtwoord;

$hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
echo $hash;
if (password_verify('', $hash) == TRUE) {
    echo "Password correct";
}
?>



<?php
if (isset($_SESSION['email'])) {
    try {
        $stmt = $conn->prepare('SELECT 
    C.password,
    C.voornaam,
    C.achternaam,
    C.email,
    A.customerid,
    A.accountopeneddate,
    A.phonenumber,
    A.faxnumber,
    A.DeliveryAddressLine1,
    A.deliveryaddressline2,
    A.deliverypostalcode
FROM
    customerlogin C
    JOIN
    customers A ON C.ID = A.customerid
    WHERE C.ID = :ID'
        );
        $stmt->execute(array(
            ':ID' => $_SESSION['ID']
        ));
        print_r($stmt->fetch());

        while ($row = $stmt->fetch()) {
            echo $row["voornaam"];
            echo $row["achternaam"];
        }
    } catch (PDOException $e) {
        $errMsg = $e->getMessage();
    }
}
?>

        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>

<a class="nav-link" href="logout.php">Logout</a>



