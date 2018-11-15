<?php
include 'session.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="afrekenen-style.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<style>

</style>
</head>
<body>
  <div class="informatieAfrekenen">
    

  </div>
<div class="row">
  <div class="col-75">
    <div class="container">
      <form action="succes.php">
      <h2>Afrekenen</h2>
<p>Vul de gegevens in en klik op "Door naar afrekenen" om de betaling af te ronden.</p>
<p>Klik op "Bestelling afbreken" om de bestelling af te breken.</p>
        <div class="row">
          <div class="col-50">
            <h3>Betaal adres</h3>
            <label for="fname"><i class="fa fa-user"></i> Naam</label>
            <input type="text" id="fname" name="Voornaam" placeholder="Henk de Groot">
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <input type="text" id="email" name="email" placeholder="hdegroot@example.com">
            <label for="adr"><i class="fa fa-address-card-o"></i> Adres</label>
            <input type="text" id="adr" name="address" placeholder="Dorpstraat 14">
            <label for="city"><i class="fa fa-institution"></i> Plaats</label>
            <input type="text" id="city" name="city" placeholder="Zwolle">

            <div class="row">
              <div class="col-50">
                <label for="zip">Postcode</label>
                <input type="text" id="zip" name="zip" placeholder="9999XY">
              </div>
            </div>
          </div>

          <div class="col-50">
            <h3>Betaalmethode</h3>
            <label for="fname">Geaccepteerde betaalkaarten</label>
            <div class="icon-container">
                <img src="ideal3.jpg" alt="ideal">
            </div>
            <label for="cname">Naam kaarthouder</label>
            <input type="text" id="cname" name="cardname" placeholder="Henk de Groot">
            <label for="ccnum">IBAN</label>
            <input type="text" id="ccnum" name="IBAN" placeholder="NL70 RABO 0123 4567 89">
            <div class="row">
              <div class="col-50">
                <label for="pasnummer">Pasnummer</label>
                <input type="text" id="pasnummer" name="pasnummer" placeholder="3522">
              </div>
            </div>
          </div>
          
        </div>
        <label>
          <input type="checkbox" checked="checked" name="sameadr"> Betaaladres zelfde als afleveradres
        </label>
        <input type="submit" value="Door naar afrekenen" class="btn">

      </form>
              <form action="index.php">
      
        <div class="row">
          <div class="col-50">
         <input type="submit" value="Betaling afbreken" class="btn">
          </div>
        </div>
    </div>
  </div>
  <div class="col-25">
    <div class="container">
      <h4>Cart <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i> <b>4</b></span></h4>
      <p><a href="#">Product 1</a> <span class="price">€15</span></p>
      <p><a href="#">Product 2</a> <span class="price">€5</span></p>
      <p><a href="#">Product 3</a> <span class="price">€8</span></p>
      <p><a href="#">Product 4</a> <span class="price">€2</span></p>
      <hr>
      <p>Totaal <span class="price" style="color:black"><b>€30</b></span></p>
    </div>
  </div>
</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>