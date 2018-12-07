<?php
include 'connection.php';
require 'session.php';

$query = 'SELECT * FROM CustomerLogin';
foreach ($conn->query($query) as $row) {
    // echo $row['Voornaam'];
}

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
    //echo $voornaam;
    try {
        $stmt = $conn->prepare('SELECT Voornaam, Achternaam, email, ID, password FROM CustomerLogin WHERE Achternaam = :Achternaam AND Voornaam = :Voornaam AND password = :wachtwoord');
        $stmt->execute(array(
            //Hier worden de eerder ingevulden variabelen ge-assigend aan de rijen in de database// 
            ':Achternaam' => $achternaam, ':Voornaam' => $voornaam, ':wachtwoord' => $wachtwoord
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data['password'] == $wachtwoord) {
            $_SESSION['voornaam'] = $data['Voornaam'];
            $_SESSION['achternaam'] = $data['Achternaam'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['ID'] = $data['ID'];
            header('Location: index.php');
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
                <div style="background-color:#006D9C; color:#FFFFFF; padding:10px;"><b>Login</b></div>
                <div style="margin: 15px">
                    <form action="" method="post">
                        <input type="text" name="voornaam" value="<?php
                if (isset($_POST['voornaam'])) {
                    echo $_POST['voornaam'];
                }
                ?>" placeholder="Voornaam" autocomplete="off" class="box"/><br /><br />
                        <input type="text" name="achternaam" value="<?php
                        if (isset($_POST['achternaam'])) {
                            echo $_POST['achternaam'];
                        }
                ?>" placeholder="Achternaam" autocomplete="off" class="box"/><br /><br />
                        <input type="password" name="wachtwoord" value="<?php
                        if (isset($_POST['wachtwoord'])) {
                            echo $_POST['wachtwoord'];
                        }
                ?>" placeholder=Wachtwoord autocomplete="off" class="box" /><br/><br />
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
