<?php

//Dit is waar de Logout knop op de index pagina naar leidt. hier wordt de session beeindigt en redirect het naar de hoofdpagina waar je vervolgens bent uitgelogd//
session_start();
session_destroy();
header("location: index.php");
?>

