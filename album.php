<?php
session_start();
include 'polaczenie.php';
$strona = (isset($_GET['strona'])) ? $_GET['strona'] : 1;
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="css/css.css">
<title>Album</title>
</head>
<body>

<?php
if(isSet($_SESSION['zalogowany']))
include 'menuzalogowany.php';
else
include 'menuniezalogowany.php';

$_SESSION['album']=$_GET['album'];

$pytanko="	
SELECT zdjecia.* , uzytkownicy.login , albumy.tytul
FROM zdjecia 
LEFT JOIN albumy ON zdjecia.id_albumu = albumy.id
LEFT JOIN uzytkownicy ON albumy.id_uzytkownika = uzytkownicy.id
WHERE id_albumu =".$_GET['album']." and zaakceptowane = 1 
ORDER BY data
LIMIT ".(($strona - 1) * 20).",20";

echo '<div id="top">
<a href="galeria.php"><div id="powrot">Powrót</div></a>';

$result = mysqli_query($polaczenie, "SELECT count(*) AS ile FROM zdjecia WHERE id_albumu =".$_GET['album']."");
$r = mysqli_fetch_assoc($result);
$stron = ceil(($r['ile'] / 20) +1);
echo '<div id="page">';

for($i= 1 ; $i<$stron ; $i++)
{	echo "<a href='album.php?album=".$_GET['album']."&strona=$i'><div id='btn2'>Strona $i</div></a>";
}

echo '</div></div>';


$wynik = mysqli_query($polaczenie,$pytanko);
$row = mysqli_fetch_array($wynik);

echo "<div id='main'>
	Zdjęcia z albumu o nazwie <u>".$row['tytul']."</u> użytkownika <u>". $row['login']."</u> 
	</div>";
	

$wynik = mysqli_query($polaczenie,$pytanko);
echo "<ul class='album'>";
while($row = mysqli_fetch_array($wynik)) {
	echo "<li><a href='foto.php?zdjecie=".$row['id']." '><img style='width:180px;border-radius:10px ; height:180px; 'src='img/".$row['id_albumu']."/".$row['id'].".jpg' ></a></li>" ;	
	}
echo '</ul>'; 
?>
<footer>
  <p>Grzegorz Jóźwiak IV TB</p>
</footer>
</body>
</html>