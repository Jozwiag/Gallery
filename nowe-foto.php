<?php
session_start();
include 'polaczenie.php';
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="css/css.css">
<title>Dodaj album</title>
</head>
<body>

<?php
if(isSet($_SESSION['zalogowany']))
include 'menuzalogowany.php';
else
include 'menuniezalogowany.php';
?>
<div id="main">Najnowsze zdjęcia</div>

<?php

$latestfotoquery= mysqli_query($polaczenie, "SELECT zdjecia.id, zdjecia.data, zdjecia.opis, uzytkownicy.id AS iduz, uzytkownicy.login, zdjecia.id_albumu
FROM zdjecia, uzytkownicy, albumy 
WHERE zdjecia.id_albumu = albumy.id and albumy.id_uzytkownika = uzytkownicy.ID
ORDER BY zdjecia.data DESC
LIMIT 20
");


echo "</div><ul class='album'>";
while($row = mysqli_fetch_array($latestfotoquery)) {
	echo "<div class='tooltip'><div class='tooltiptext'>". $row['login']."<br>".$row['data'] ."</div>
	<li><a href='foto.php?album=".$row['id_albumu']."&zdjecie=".$row['id']."' ><img style='border-radius: 10px; width:180px ; height:180px; 'src='img/".$row['id_albumu']."/".$row['id'].".jpg' > </a></li></div>" ;	
}
echo "</ul>";
?>
<footer>
  <p>Grzegorz Jóźwiak IV TB</p>
</footer>
</body>
</html>