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
if (isSet($_SESSION['zalogowany'])) include 'menuzalogowany.php';
else include 'menuniezalogowany.php';

$topfotoquery= mysqli_query($polaczenie, "SELECT AVG(ocena) AS sr_ocena, zdjecia.*, uzytkownicy.login
FROM zdjecia_oceny
	LEFT JOIN zdjecia ON zdjecia_oceny.id_zdjecia=zdjecia.id
	LEFT JOIN uzytkownicy ON zdjecia_oceny.id_uzytkownika=uzytkownicy.ID
GROUP BY id
ORDER BY sr_ocena desc
LIMIT 20");

?>
<div id="main">Najwyżej oceniane</div>

<?php

echo "</div><ul class='album'>";
while($row = mysqli_fetch_array($topfotoquery)) {
	echo "<div class='tooltip'><div class='tooltiptext'>Śr ocena:&nbsp".(double)$row['sr_ocena']."<br>". $row['login']."<br>".$row['data'] ."</div>
	<li><a href='foto.php?album=".$row['id_albumu']."&zdjecie=".$row['id']."' ><img style='border-radius: 10px; width:180px ; height:180px; 'src='img/".$row['id_albumu']."/".$row['id'].".jpg' > </a></li></div>" ;	
}
echo "</ul>";


?>
<footer>
  <p>Grzegorz Jóźwiak IV TB</p>
</footer>
</body>
</html>