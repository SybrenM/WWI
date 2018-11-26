
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

        <title>Hello, world!</title>
    </head>
    <?php
//Dit is de pagina waar je naar verwezen wordt als je op "Login' klikt op de index pagina.
    include 'session.php';
    include 'connection.php';
    include 'functions.php';
    if (isset($_POST['login'])) {
        $errMsg = '';
        // Deze isset functie controleerd telkens of je verplichte velden hebt leeggelaten//
        $voornaam = $_POST['voornaam'];
        $achternaam = $_POST['achternaam'];
        $wachtwoord = $_POST['wachtwoord'];
        if ($voornaam == '') {
            $errMsg = 'Vul voornaam in';
        }
        if ($achternaam == '') {
            $errMsg = 'Vul achternaam in';
        }
        if ($wachtwoord == '') {
            $errMsg = 'Vul wachtwoord in';
        }
        //De voornaam en achternaam wordt gebonden omdat dit zo is genoteerd in de database
        $fullName = $voornaam . $achternaam;
        try {
            $stmt = $conn->prepare('SELECT * FROM people WHERE FullName = :naam');
            $stmt->execute(array(
                //Hier worden de eerder ingevulden variabelen ge-assigend aan de rijen in de database// 
                ':naam' => $fullName
            ));
            $data = $stmt->fetch();
            //Hier wordt gekeken of het gehashte wachtwoord in de database overeenkomt met het ingevulde wachtwoord.//
            if (password_verify($wachtwoord, $data['HashedPassword'])) {
                //Hier wordt alle informatie uit de kolommen in sessies opgeslagen. Dit zorgt ervoor dat we deze informatie altijd kunnen ophalen na het inloggen//
                $_SESSION['voornaam'] = $data['FullName'];
                $_SESSION['achternaam'] = $data['PreferredName'];
                $_SESSION['email'] = $data['EmailAddress'];
                $_SESSION['ID'] = $data['PersonID'];
                $_SESSION['IsPermittedToLogon'] = $data['IsPermittedToLogon'];
                $_SESSION['IsExternalLogonProvider'] = $data['IsExternalLogonProvider'];
                $_SESSION['IsSystemUser'] = $data['IsSystemUser'];
                $_SESSION['IsEmployee'] = $data['IsEmployee'];
                $_SESSION['IsSalesPerson'] = $data['IsSalesPerson'];
                header('Location: index.php');
            } else {
                $errMsg = "Inloggen mislukt";
            }
        } catch (PDOException $e) {
            $errMsg = $e->getMessage();
        }
    }
    ?>

    <body><?php include 'navbar.php'; ?>

        <div class="container">

            <h1> Inloggen </h1>


            <?php
            if (isset($errMsg)) {
                echo '<div style="color:#FF0000;text-align:center;font-size:17px;">' . $errMsg . '</div>';
            }
            ?>
            <form action="" method="post">
                <input type="text" name="voornaam" placeholder="Voornaam" autocomplete="off" class="box"/><br /><br />
                <input type="text" name="achternaam"placeholder="Achternaam" autocomplete="off" class="box"/><br /><br />
                <input type="password" name="wachtwoord" placeholder="Wachtwoord" autocomplete="off" class="box" /><br/><br />
                <input type="submit" class="btn btn-primary" name='login' value="Login" class='submit'/><br />
            </form>

        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
