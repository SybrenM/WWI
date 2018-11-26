<?php
//Dit is de pagina waar je naar verwezen wordt als je op "Login' klikt op de index pagina.
include 'connection.php';
require 'session.php';

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
            $errMsg = "Login Failed. Password does not match";
        }
    } catch (PDOException $e) {
        $errMsg = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
    <head><title>Login</title></head>
    <style>
        html, body {
            margin: 1px;
            border: 0;
        }
    </style>
    <body>
        <div align="center">
            <div style=" border: solid 1px #006D9C; " align="left">
                <?php
                if (isset($errMsg)) {
                    echo '<div style="color:#FF0000;text-align:center;font-size:17px;">' . $errMsg . '</div>';
                }
                ?>
                <!--Hier wordt de voornaam, achternaam en het wachtwoord opgehaald-->
                <div style="background-color:#006D9C; color:#FFFFFF; padding:10px;"><b>Login</b></div>
                <div style="margin: 15px">
                    <form action="" method="post">
                       
                        <input placeholder="email" autocomplete="off" class="box"/><br /><br />
                        <input type="text" name="email"
                               placeholder="Wachtwoord" autocomplete="off" class="box"/><br /><br />
                        <input type="password" name="wachtwoord" 
                               placeholder=Wachtwoord autocomplete="off" class="box" /><br/><br />
                        <input type="submit" name='login' value="Login" class='submit'/><br />
                    </form>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>
