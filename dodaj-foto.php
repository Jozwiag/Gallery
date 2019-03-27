<?php
session_start();
include 'polaczenie.php';
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="css/css.css">
<title>Dodaj zdjęcie</title>
</head>
<body>

<?php
if(isSet($_SESSION['zalogowany']))
include 'menuzalogowany.php';
else
include 'menuniezalogowany.php';

?>


<?php



$pytanko= "SELECT albumy.*, min(zdjecia.id) AS foto_id, uzytkownicy.login
FROM albumy
	LEFT JOIN zdjecia ON albumy.id=zdjecia.id_albumu
	LEFT JOIN uzytkownicy ON albumy.id_uzytkownika=uzytkownicy.id
WHERE id_uzytkownika='".$_SESSION['id']."'
GROUP BY albumy.id
ORDER BY foto_id DESC
LIMIT 0,20";

$wynik = mysqli_query($polaczenie,$pytanko);

if(isset($_GET['album']))
{
	$pytankoalbum= "SELECT albumy.*, min(zdjecia.id) AS foto_id, uzytkownicy.login
		FROM albumy
			LEFT JOIN zdjecia ON albumy.id=zdjecia.id_albumu
			LEFT JOIN uzytkownicy ON albumy.id_uzytkownika=uzytkownicy.id
		WHERE albumy.id='".$_GET['album']."'
		GROUP BY zdjecia.id
		ORDER BY foto_id DESC
		LIMIT 0,20";
		
	$wynikalbum = mysqli_query($polaczenie,$pytankoalbum);
	$row = mysqli_fetch_array($wynikalbum);
	$_SESSION['idalbumu'] = $_GET['album'];
	echo '<div id="dodanie">
			<div id="main">
				<br>Dodawanie zdjęć do albumu o nazwie <u>'.$row["tytul"].'</u>
			</div>
			<form enctype="multipart/form-data" action="wysylaniezdjecia.php" method="post" >
			<input id="form" type="file" name="foto" />
			<input id="form" type="text" name="opis" placeholder="Opis zdjęcia" title="Maksymalnie 255 znaków" maxlength="255" ><br>
			<input id="submit" type="submit" value="Dodaj zdjęcie" />
			</form>
		</div><br><br>';
		$wynikdwa = mysqli_query($polaczenie, "SELECT id FROM zdjecia WHERE id_albumu=".$_GET['album']."");
		echo "<ul class='album'>";
		while($rowdwa = mysqli_fetch_array($wynikdwa)) {
			echo "<li><img style='width:180px ;margin:10px;border-radius: 10px; ; height:180px; 'src='img/".$_GET['album']."/".$rowdwa['id'].".jpg' ></li>" ;	
		}
		echo '</ul>'; 
		
		
}
else {
	if(mysqli_num_rows($wynik)>0)
		{
			echo "<div id='main'>
						Twoje albumy:
						</div>";
			echo "<ul class='album'>";
			while($row = mysqli_fetch_array($wynik)) {
				if(isset($row['foto_id']))
				{
					echo "<div class='tooltip'><div class='tooltiptext'>".$row['tytul']."<br>". $row['login']."<br>".$row['data'] ."</div>	
					<li><a href='dodaj-foto.php?album=".$row['id']." '><img style='border-radius: 10px; width:180px ; height:180px; 'src='img/".$row['id']."/".$row['foto_id'].".jpg' > </a></li></div>" ;	
				}
				else
				{
					echo "<div class='tooltip'><div class='tooltiptext'>".$row['tytul']."<br>". $row['login']."<br>".$row['data'] ."</div>	
					<li><a href='dodaj-foto.php?album=".$row['id']." '><img style='border-radius: 10px; width:180px ; height:180px; 'src='css/brak.png' > </a></li></div>" ;	
				}
				
			}
			echo '</ul>'; 
		}
	else
	{
		echo "<div id='main'>
			Nie masz żadnych albumów
			</div>";
	}
}
?>

<footer>
  <p>Grzegorz Jóźwiak IV TB</p>
</footer>
</body>
</html>