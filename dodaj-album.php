<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="css/css.css">
    <title>Dodaj album
    </title>
  </head>
  <body>
    <?php
if(isSet($_SESSION['zalogowany']))
include 'menuzalogowany.php';
else
include 'menuniezalogowany.php';
?>
    <div id="main">
      Dodaj album
    </div>
    <form action="wysylaniealbumu.php" method="POST">
      <input id="form" type="text" name="album" placeholder="Nazwa albumu" pattern="{3,100}" title="Od 3 do 100 znaków" required><br>
      <input id="submit" type="submit" value="Zalóż album">   
    </form>
	
<footer>
<p>Grzegorz Jóźwiak IV TB
</p>
</footer>
</body>
</html>
