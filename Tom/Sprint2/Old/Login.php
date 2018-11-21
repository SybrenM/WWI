<!DOCTYPE html>
  <?php include 'LoginFunctions.php';
        include 'session.php';?>

<form id='login' action='login.php' method='post' accept-charset='UTF-8'>
    <fieldset >
        <legend>Login</legend>
        <input type='hidden' name='submitted' id='submitted' value='1'/>

        <label for='username' >Voornaam:</label>
        <input type='text' name='voornaam' id='voornaam' maxlength='50'/>
        <BR>
        <label for='username' >Achternaam:</label>
        <input type='text' name='achternaam' id='achternaam' maxlength='50' />
        <BR>
        <label for='password' >Wachtwoord:</label>
        <input type='password' name='wachtwoord' id='wachtwoord' maxlength='50'/>

        <input type='submit' name='Inloggen' value='Inloggen' />

    </fieldset>             
</form>

<?php
if(isset($_POST['Inloggen'])) {
 
$voornaam = $_POST['voornaam'];
echo $voornaam;
echo "Submission succesful";
} else {
    echo "Tuberculosis";
}



?>



