<?php
session_start();
include 'polaczenie.php';


if(!isset($_SESSION['sort']))
	$_SESSION['sort'] = 'tytul';
if(isset($_GET['sort']))
	$_SESSION['sort'] = $_GET['sort'];
$strona = (isset($_GET['strona'])) ? $_GET['strona'] : 1;
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="css/css.css">
<title>Galeria</title>
</head>
<body>



<?php
	if(isSet($_SESSION['zalogowany']))
		include 'menuzalogowany.php';
	else
		include 'menuniezalogowany.php';
	
	$pytanko= "SELECT albumy.*, min(zdjecia.id) AS foto_id, uzytkownicy.login
	FROM albumy
		LEFT JOIN zdjecia ON albumy.id=zdjecia.id_albumu
		LEFT JOIN uzytkownicy ON albumy.id_uzytkownika=uzytkownicy.id
	WHERE zdjecia.zaakceptowane
	GROUP BY albumy.id
	ORDER BY ".$_SESSION['sort']." 
	LIMIT ".(($strona - 1) * 20).",20";



echo '<div id="top"><div id="sort">
<b>Sortuj:</b>
<a href="galeria.php?sort=tytul"><div id="btn1">&nbspTytuł</div></a>
<a href="galeria.php?sort=data"><div id="btn1">Data dodania</div></a>
<a href="galeria.php?sort=login"><div id="btn1">Nick</div></a>
</div>';

$result = mysqli_query($polaczenie, "SELECT count(*) AS ile FROM albumy WHERE id=(SELECT id_albumu FROM zdjecia WHERE zaakceptowane = 1 GROUP BY id_albumu)");
$r = mysqli_fetch_assoc($result);
$stron = ceil(($r['ile'] / 20) +1);
echo '<div id="page">';

for($i= 1 ; $i<$stron ; $i++)
{	echo "<a href='galeria.php?strona=$i'><div id='btn2'>Strona $i</div></a>	";
}

echo '</div></div>';

$wynik = mysqli_query($polaczenie,$pytanko);

echo "<ul class='album'>";
if($wynik){
	while($row = mysqli_fetch_array($wynik)) {
		echo "<div class='tooltip'>
			<div class='tooltiptext'>".$row['tytul']."<br>". $row['login']."<br>".$row['data'] ."</div>
			<li><a href='album.php?album=".$row['id']."' ><img style='border-radius: 10px; width:180px ; height:180px; 'src='img/".$row['id']."/".$row['foto_id'].".jpg' > </a></li>
			</div>" ;	
		
	}
}
echo "</ul>";

?>

<footer>
  <p>Grzegorz Jóźwiak IV TB</p>
	
</footer>
</body>
</html>