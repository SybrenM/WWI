<?php

function Login($voornaam, $achternaam, $wachtwoord) {
    if (empty($_POST['voornaam'])) {
        $this->HandleError("Voornaam is niet ingevuld!");
        return false;
    }
    if (empty($_POST['achternaam'])) {
        $this->HandleError("Achternaam is niet ingevuld!");
        return false;
    }
    if (empty($_POST['wachtwoord'])) {
        $this->HandleError("Wachtwoord is niet ingevuld!");
        return false;
    }

    $voornaam = trim($_POST['voornaam']);
    $achternaam = trim($_POST['achternaam']);
    $wachtwoord = trim($_POST['wachtwoord'] . "GAY");

    if (!$this->CheckLoginInDB($username, $password)) {
        return false;
    }


    session_start();

    $_SESSION[$this->GetLoginSessionVar()] = $username;

    return true;
}

function CheckLoginDB($username, $password) {
    include connection . php;
    if (!$this->DBLogin()) {
        $this->HandleError("Database login FAILED");
        return false;
    }
    $username = $this->SanitizeForSQL($username);
    #$pwdm5 = md5($password);
    $query = $conn->query("SELECT name FROM CustomerLogin" . "WHERE voornaam='$username' and password='$password' ");


    $result = mysql_query($querry, $this->connection);

    if (!$result || mysql_num_rows($result) <= 0) {
        $this->HandleError("Error Logging in. " . "The username of password does not match");
        return false;
    }
    return true;
}

function Test($voornaam) {
    echo $voornaam;
}
?>