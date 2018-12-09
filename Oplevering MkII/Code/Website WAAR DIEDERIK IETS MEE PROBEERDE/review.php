<?php
include 'session.php';
include 'connection.php';
//Als $_SESSION['email'] bestaat, dan kan je een review achterlaten. als dit niet het geval is, dan wordt je herleid naar de index pagina, zo kunnen mensen niet via de addressbar naar de review.php pagina gaans
if (isset($_SESSION['email'])) {
                $artikelID = filter_input(INPUT_GET, "artikelID", FILTER_SANITIZE_STRING); //Het artikelID van het weer te geven artikel

    ?>
    <!doctype html>
    <html lang="en">
        <head>
            <!-- Meta tags die belangrijk zijn -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <!-- Bootstrap CSS -->

            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
            <link rel="stylesheet" type="text/css" href="artikel-style.css">
            <link rel="stylesheet" type="text/css" href="css.css">
            <link rel="stylesheet" type="text/css" href="style.css">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

            <title>Klantinformatie</title> 
        </head>
        <body>
            
<nav class="navbar navbar-expand-lg navbar-light"
     <!-- Hier is de home-knop -->
     <a class="navbar-brand" href="index.php">WideWorldImporters</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
  
            
            <li class="nav-item">
                        <a class="nav-link" href="artikel.php?artikelid=<?php echo $artikelID ?>&maatselected=TRUE">< Terug</a>
                    </li>
        </ul>
    </div>
</nav>

            <?php
         
            //Hier wordt het artikelID in een variabele gezet. het artikelID komt mee met de header en wordt hier eruit gehaald
            //Issues met gestuurde headers die niet kunnen worden verandert. :/

       

//Als op de submit knop wordt geklikt, dan wordt de ingevoerde informatie in variabelen gestopt. deze informatie wordt in de review database gestopt
            if (isset($_POST['submit'])) {
                $reviewname = $_POST['reviewname'];
                $review = $_POST['review'];
                $rating = $_POST['rating'];
            }

//hier wordt een ID aangemaakt voor de riview. de 'reviewid' is dus ook een primary key en is voor elke review anders
            $reviewid = $conn->query('SELECT MAX(reviewid) AS ID FROM review');
            $id = 0;
            while ($number = $reviewid->fetch()) {
                $id += $number["ID"] + 1;
            }

            //Zodra op submit geklikt wordt, wordt de ingevoerde informatie in de review-tabel gestopt.
            if (isset($_POST['submit'])) {
                try {
                    $stmt = $conn->prepare('INSERT INTO review (StockItemID, PersonID, review, reviewid, reviewname, rating, date) VALUES (:StockItemID, :PersonID, :review, :reviewid, :reviewname, :rating, :date)');
                    $stmt->execute(array(
                        ':StockItemID' => $artikelID, ':PersonID' => $_SESSION['ID'], ':review' => $review, ':reviewid' => $id, ':reviewname' => $reviewname, ':rating' => $rating, ':date' => date("Y-m-d")));
                    #header('Location: http://localhost/KBS/Tom/Sprint3/artikel.php?artikelid=' . $artikelID . '&maatselected=FALSE');
                    //de header leidt terug naar de originele pagina/product waar de review komt te staan
                                       //exit;
                                            
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
             //   echo "asdasd";
                      header('Location: artikel.php?artikelid=' . $artikelID.'&maatselected=TRUE');
            } else {
                $errMsg = "Something went wrong";
            }
  
            ?>

            
            
            <!--HTML-->
            <form method="post" >
                <div class="container"> 
                    <div style="padding-bottom: 18px;font-size : 24px;">Product Review</div>
                    <div style="padding-bottom: 18px;">Rate this product<span style="color: red;"> *</span><br/>
                        <select id="data_4" name="rating" style="width : 150px;" class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div style="padding-bottom: 18px;">Title<span style="color: red;"> *</span><br/>
                        <input type="text" id="data_5" name="reviewname" style="width : 450px;" class="form-control"/>
                    </div>
                    <div style="padding-bottom: 18px;">Review<span style="color: red;"> *</span><br/>
                        <textarea id="data_8" false name="review" style="width : 450px;" rows="9" class="form-control"></textarea>
                    </div>
                    <div style="padding-bottom: 18px;"><input name="submit" value="Submit" type="submit"/></div>
                </div>
            </form>
        </body>
    <!--Insert Code Here-->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
    </html>
    
    <!--Einde if-statement-->
    <?php
} else {
    header('Location: index.php');
}
?>