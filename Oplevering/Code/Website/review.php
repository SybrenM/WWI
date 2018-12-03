<?php

  include 'session.php';
    include 'connection.php';
    
$artikelID = filter_input(INPUT_GET, "artikelID", FILTER_SANITIZE_STRING); //Het artikelID van het weer te geven artikel

if(isset($_POST['submit'])) {
$reviewname = $_POST['reviewname'];
$review = $_POST['review'];
$rating = $_POST['rating'];

}

if(isset($_SESSION['ArtikelID'])) {
    echo $artikelID;
} else {
    echo "Session not set";
}



    
    $reviewid = $conn->query('SELECT MAX(reviewid) AS ID FROM review');
        $id = 0;
        while ($number = $reviewid->fetch()) {
            $id += $number["ID"] + 1;
        }
        
        if (isset($_POST['submit'])) {
    try {
        $stmt = $conn->prepare('INSERT INTO review (StockItemID, PersonID, review, reviewid, reviewname, rating, date) VALUES (:StockItemID, :PersonID, :review, :reviewid, :reviewname, :rating, :date)');
        $stmt->execute(array(
            ':StockItemID' => $artikelID, ':PersonID' => $_SESSION['ID'], ':review' => $review, ':reviewid' => $id, ':reviewname' => $reviewname, ':rating' => $rating, ':date' => date("Y-m-d")));
         header('Location: artikel.php?artikelid=' . $artikelID);
        exit;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    $errMsg = "Something went wrong";
}
?>




<form method="post" >
    <div style="width: 400px;">
    </div>
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
    <div>

    </div>
</form>