<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="css/css.css">
<title>Rejestracja zakończona powodzeniem</title>
</head>
<body>

<?php
if(isSet($_SESSION['zalogowany']))
include 'menuzalogowany.php';
else
include 'menuniezalogowany.php';
?>

<div id="main">
Zostałeś zarejestrowany
</div>

<form action="galeria.php">
<input id="submit" type="submit" value="Przejdź do galerii"/>
</form>


<footer>
  <p>Grzegorz Jóźwiak IV TB</p>
</footer>
</body>
</html>