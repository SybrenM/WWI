<?php

//Dit is waar de Logout knop op de index pagina naar leidt. hier wordt de session beeindigt en redirect het naar de hoofdpagina waar je vervolgens bent uitgelogd//
session_start();
//session_destroy();
                
unset($_SESSION['email']);
unset($_SESSION['IsSystemUser']);
unset($_SESSION['ID']);

header("location: index.php");
?>

