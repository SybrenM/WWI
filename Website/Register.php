<!DOCTYPE html>
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
            $stmt = $conn->prepare("SELECT COUNT(EmailAddress) as Email FROM people WHERE EmailAddress = :email");
            $stmt -> execute(array(':email'=>$email));
            while($query = $stmt->fetch()){
                $countEmail = $query["Email"];
            }
            if($countEmail < 1){
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
        } else { $errMsg = "Deze Emailadres is al geregistreerd"; }
        }
    }
//Als op de "register' knop wordt gedrukt dan wordt een button zichtbaar die je herleid naar de homepage//
    if (isset($_GET['action']) && $_GET['action'] == 'joined') {
        $errMsg = 'Registration succesful. Now you can <a href="LoginMain.php">Login</a>';
    }
    ?>

    <!DOCTYPE html>
    <body><?php include 'navbar.php'; ?>

        <div class="container">

            <h1> Registreren </h1>


            <?php
            if (isset($errMsg)) {
                echo '<div style="color:#FF0000;text-align:center;font-size:17px;">' . $errMsg . '</div>';
            }
            ?>
            <form action="" method="post">

                <input type="text" name="voornaam" placeholder="Voornaam"         
                       autocomplete="off" class="box"/><br /><br />
                <input type="text" name="achternaam" placeholder="Achternaam" 
                       autocomplete="off" class="box"/><br /><br />
                <input type="text" name="email" placeholder="E-Mail" 
                       class="box" /><br/><br />
                <input type="password" name="wachtwoord" placeholder="Wachtwoord" 
                       class="box" /><br/><br />
                                   <input type="submit" class="btn btn-primary" name='register' value="Registreer" class='submit'/><br />
            </form>
        </div>



        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>

<!-- Hier wordt de informatie opgehaald-->

                    