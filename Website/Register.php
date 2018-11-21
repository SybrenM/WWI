<?php
//Dit is de pagina waarnaar je wordt verwezen als je op de 'Register' knop klikt op de index pagina.
include 'session.php';
require 'connection.php';

if (isset($_POST['register'])) {
    $errMsg = '';
//Hier wordt de ingevulde informatie opgehaald en gedefinieerd//
    $voornaam = $_POST['voornaam'];
    $achternaam = $_POST['achternaam'];
    //Het gegeven wachtwoord wordt gehashed//
    $passwordhash = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    if ($voornaam == '') {
        $errMsg = "Vul voornaam in";
    }
    if ($achternaam == '') {
        $errMsg = "Vul achternaam in";
    }
    if ($passwordhash == '') {
        $errMsg = "Vul wachtwoord in";
    }
    if ($email == '') {
        $errMsg = "Vul email in";
    }

    //Hier wordt een ID gecreeeeerd//
    $IDnumber = $conn->query('SELECT MAX(PersonID) AS ID FROM people');
    $klantID = 0;
    while ($number = $IDnumber->fetch()) {
        $klantID += $number["ID"] + 1;
    }
    if ($errMsg == '') {
        $fullName = $voornaam . $achternaam;
        try {
            $stmt = $conn->prepare('INSERT INTO people (PersonID, FullName, EmailAddress, HashedPassword, LastEditedBy) VALUES (:ID, :naam, :email, :wachtwoord, 1)');
            $stmt->execute(array(
                //De ingevulde informatie wordt in de database gezet//
                ':naam' => $fullName, ':wachtwoord' => $passwordhash, ':email' => $email, ':ID' => $klantID));
            header('Location: Register.php?action=joined');
            exit;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
//Als op de "register' knop wordt gedrukt dan wordt een button zichtbaar die je herleid naar de homepage//
if (isset($_GET['action']) && $_GET['action'] == 'joined') {
    $errMsg = 'Registration succesful. Now you can <a href="LoginMain.php">Login</a>';
}
?>

<!DOCTYPE html>
<html>
    <head><title>Register</title></head>
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
                
                <!-- Hier wordt de informatie opgehaald-->
                <div style="background-color:#006D9C; color:#FFFFFF; padding:10px;"><b>Register</b></div>
                <div style="margin: 15px">
                    <form action="" method="post">
                        <input type="text" name="voornaam" placeholder="Voornaam" value="<?php
                        if (isset($_POST['voornaam'])) {
                            echo $_POST['voornaam'];
                        }
                        ?>" autocomplete="off" class="box"/><br /><br />
                        <input type="text" name="achternaam" placeholder="Achternaam" value="<?php
                        if (isset($_POST['achternaam'])) {
                            echo $_POST['achternaam'];
                        }
                        ?>" autocomplete="off" class="box"/><br /><br />
                        <input type="text" name="email" placeholder="E-Mail" value="<?php
                        if (isset($_POST['email'])) {
                            echo $_POST['email'];
                        }
                        ?>" class="box" /><br/><br />
                        <input type="password" name="wachtwoord" placeholder="Wachtwoord" value="<?php
                        if (isset($_POST['wachtwoord'])) {
                            echo $_POST['wachtwoord'];
                        }
                        ?>" class="box" /><br/><br />

                        <input type="submit" name='register' value="Register" class='submit'/><br />
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

