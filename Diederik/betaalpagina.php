<?php
include 'session.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'connection.php';
        ?>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <title></title>

    </head>
    <body><?php
        $row = $conn->query("SELECT count(*) FROM stockgroups");
        while ($artikel = $row->fetch()) {
            $max = $artikel["count(*)"];
        }
        
        include 'navbar.php';
        ?>
        
        <div class="container">
      
    <button type="button" class="btn btn-verder btn-lg">  <a href="index.php">Verder Winkelen</a></button>
    <button type="button" class="btn btn-verder btn-lg">  <a href="afrekenen.php">Afrekenen</a></button>  
        </div>
    </body>
</html>
