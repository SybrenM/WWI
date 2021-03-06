<?php
//Hier bevindt zich de belangrijste core van onze website: Sessies, de connectie met de database, en functies
include 'session.php';
include 'connection.php';
include 'functions.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <title></title>

    </head>
    <body>
        
        <!-- Hier wordt de navbar opgevraagd -->
        <?php include 'navbar.php'; ?>
        <!-- -->
        <div class="container">
            <div class="row">
                <!-- Hier komt de grote hoofdfoto -->
                <div class="col-lg-12 bigPicture">
                    <img src="artikelFoto/placeholder.jpg" alt="placeholder1" height="500px" width="100%">
                </div>       
            </div>  
            <!-- In deze row komen 3 kleine fototjes -->
            <div class="row"> 
                <div class="col-lg-4 smallPicture">
                    <img src="artikelFoto/placeholder.jpg" alt="placeholder1"  height="300px" width="100%">
                </div>
                <div class="col-lg-4 smallPicture">

                    <img src="artikelFoto/placeholder.jpg" alt="placeholder2"  height="300px" width="100%">
                </div>
                <div class="col-lg-4 smallPicture">
                    <img src="artikelFoto/placeholder.jpg" alt="placeholder3"  height="300px" width="100%">
                </div>
            </div>    


            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>
