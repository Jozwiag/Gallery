<?php
	session_start();
	include 'polaczenie.php';
	if(!isset($_GET['mk'])) 	
		$_GET['mk'] = 0;
	if(!isset($_GET['md'])) 	
		$_GET['md'] = 0;
	if(!isset($_POST['nopis'])) 	
		$_POST['nopis'] = 0;
		
?>

<!DOCTYPE html>

<html>
	<head>
    	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    	<link rel="stylesheet" href="css/css.css">
    	<title>Moje konto</title>
	</head>
	<body>
   		<?php
        if(isSet($_SESSION['zalogowany']))
        	include 'menuzalogowany.php';
        	else
        	include 'menuniezalogowany.php';
		?>
    <div id="main">
		Moje konto
	</div>
	<div id ="container">
    	<a href="konto.php?mk=1">
        	<div id="dane">Moje dane</div>
        </a>
        <a href="konto.php?mk=2">
        	<div id="dane">Moje albumy</div>
        </a>
        <a href="konto.php?mk=3">
        	<div id="dane">Moje zdjecia</div>
        </a>
    </div>
    <?php
    	if($_GET['mk'] == 1) {
        	echo '<div id="container">
         		  <a href="konto.php?mk=1&md=1"><div id="zmiana">Zmień hasło</div></a>
         		  <a href="konto.php?mk=1&md=2"><div id="zmiana">Zmień e-mail</div></a>
         		  </div>';
         	if($_GET['md'] == 1)
         	{
         		echo '<form action="zmianahasla.php">
         			  <input id="form" placeholder="Hasło" type="password" name="haslo" required>
         			  <br>
         			  <input id="form" placeholder="Nowe hasło" type="password" name="nhaslo" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}" title="Conajmniej 1 duża litera, 1 mała litera i 1 cyfra, od 6 do 20 znaków" required>
         			  <br>
         			  <input id="form" placeholder="Potwierdź nowe hasło" type="password" name="nhaslo2" required>
         			  <br>
         			  <input id="submit" type="submit" value="Zmień hasło">   
         			  </form>';
         	}
         	if($_GET['md'] == 2)
         	{
         		echo '<form action="zmianaemail.php">
         			  <input id="form" placeholder="Hasło" type="password" name="haslo" required>
         			  <br>
         			  <input id="form" placeholder="Nowy e-mail" type="text" name="email" input pattern=".{0,128}" required>
         			  <br>
         			  <input id="submit" type="submit" value="Zmień e-mail">   
         			  </form>';
         	}
        }
         
        if($_GET['mk'] == 2) {
         	$pytanko = "SELECT albumy.*, min(zdjecia.id) AS foto_id, uzytkownicy.login
         				FROM albumy
         					LEFT JOIN zdjecia ON albumy.id=zdjecia.id_albumu
         					LEFT JOIN uzytkownicy ON albumy.id_uzytkownika=uzytkownicy.id
         				WHERE uzytkownicy.id = ".$_SESSION['id']." 
						GROUP BY albumy.id";
						 
         	$wynik = mysqli_query($polaczenie,$pytanko);
         	echo '</div></div>';
         	
         	$albumy = mysqli_fetch_array($wynik);
         			
         	if(isset($_GET['zmt'])) {
         		$_SESSION['idalbumu'] = $_GET['album'];
         		echo '<form action="konto.php?mk=2&zmt&album='.$_SESSION['idalbumu'].'" method="POST">
         			  <input id="form" placeholder="Nowy tytuł albumu" type="text" name="ntytul" required>
         			  <input id="submit" type="submit" value="Zmień tytuł albumu">   
					  </form>';	
					   
         		
							
         		if(isset($_POST['ntytul'])) {
					$nalbum = "UPDATE albumy SET tytul ='".$_POST['ntytul']."'
					WHERE albumy.ID=".$_SESSION['idalbumu']."";
					$tytulalbumu = mysqli_query($polaczenie,$nalbum);
         			echo "<script type='text/javascript'>alert('Pomyślnie zmieniono tytuł albumu');</script>";
         			header('Refresh: 0; url=konto.php?mk=2');
         		}
         			
         	}
         		
         	elseif(isset($_GET['usn'])) {		
         		$_SESSION['idalbumu'] = $_GET['album'];
         		echo '<script type="text/javascript">
         			  if (confirm("Usunąć folder? Tego procesu nie będzie można cofnąć!"))
         			  location.href="usunfolder.php?album='.$_SESSION['idalbumu'].'";
         			  </script>';
         		}
         		
         	else {
				echo '<div id="main">Możesz usunąć lub zmienić nazwę albumu</div>';
				
				echo "<ul class='album'>";
         		while($row = mysqli_fetch_array($wynik)) {
					if(isset($row['foto_id'])){
						echo "<div class='tooltip'><div class='tooltiptext'>".$row['tytul']."<br><br>
							<a href='konto.php?mk=2&zmt&album=".$row['id']."'><div id='btn3'>Zmień tytuł</div></a>
							<a href='konto.php?mk=2&usn&album=".$row['id']."'><div id='btn3'>Usuń album</div></a>
							</div>
							<li id='center'><img style='border-radius: 10px; width:180px ; height:180px; 'src='img/".$row['id']."/".$row['foto_id'].".jpg'></li></div>" ;	
					}
					else{
						echo "<div class='tooltip'><div class='tooltiptext'>".$row['tytul']."<br><br>
							<a href='konto.php?mk=2&zmt&album=".$row['id']."'><div id='btn3'>Zmień tytuł</div></a>
							<a href='konto.php?mk=2&usn&album=".$row['id']."'><div id='btn3'>Usuń album</div></a>
							</div>
							<li id='center'><img style='border-radius: 10px; width:180px ; height:180px; 'src='css/brak.png'></li></div>" ;
					}
				}
         	}
        echo "</ul>";
      	}
         
    	if($_GET['mk'] == 3){
			if(isset($_GET['zmo']))
			{
     		    $_SESSION['album']= $_GET['album'];
         		$_SESSION['idzdj'] = $_GET['zdjecie'];
         		echo '<form action="konto.php?mk=3&zmo&zdjecie='.$_SESSION["idzdj"].'" method="POST">
         			  <input id="form" placeholder="Nowy opis zdjęcia" type="text" name="nopis" required>
         			  <input id="submit" type="submit" value="Zmień opis zdjęcia">   
         			  </form>';	
         	    
                if($_POST['nopis'] !='')
                {
					$nopis = "UPDATE zdjecia SET opis ='".$_POST['nopis']."'
         				  	  WHERE zdjecia.ID=".$_SESSION['idzdj']."";
					$nopiswynik = mysqli_query($polaczenie,$nopis);
					echo "<script type='text/javascript'>alert('Pomyślnie zmieniono opis zdjęcia');</script>";
					header("Refresh:0; url=konto.php?mk=3");
					}
					
			} 
         	elseif(isset($_GET['usn']))
         	{
				$usunzdj="DELETE FROM zdjecia WHERE id=".$_GET['zdjecie'];
					
				$ocenusun= "DELETE FROM zdjecia_oceny 
				 			WHERE id_zdjecia=(SELECT id FROM zdjecia WHERE id=".$_SESSION['idzdj'].")";
					
				$komusun= "DELETE FROM zdjecia_komentarze 
						   WHERE id_zdjecia=(SELECT id FROM zdjecia WHERE id=".$_SESSION['idzdj'].")";
					
				$usunocenawynik= mysqli_query($polaczenie,$ocenusun);
				$usunkomwynik= mysqli_query($polaczenie,$komusun);
				$usunzdjwynik= mysqli_query($polaczenie,$usunzdj);
				$plik = "img/".$_GET['album']."/".$_GET['zdjecie'].".jpg";
				unlink($plik);
				echo "<script type='text/javascript'>alert('Pomyślnie usunięto zdjęcie');</script>";
				header("Refresh:0; url=konto.php?mk=3&album=".$_GET['album']);
         	}
         	elseif(isset($_GET['album']) )
         	{
         		$_SESSION['album']=$_GET['album'];
         
				$pytanko=	
         		"SELECT zdjecia.* , uzytkownicy.login , albumy.tytul
         		FROM zdjecia 
         		LEFT JOIN albumy ON zdjecia.id_albumu = albumy.id
         		LEFT JOIN uzytkownicy ON albumy.id_uzytkownika = uzytkownicy.id
         		WHERE id_albumu =".$_GET['album']." and zaakceptowane = 1 
         		ORDER BY data";
         
         
         		$wynik = mysqli_query($polaczenie,$pytanko);
         		$row = mysqli_fetch_array($wynik);
         
         		echo "<div id='main'>
         			  Zdjęcia z albumu o nazwie <u>".$row['tytul']."</u>
         			  </div>";
         							
         
         		$wynik = mysqli_query($polaczenie,$pytanko);
         		echo "<ul class='album'>";
         		while($row = mysqli_fetch_array($wynik)) {
         			echo "<div class='tooltip'><div class='tooltiptext'>".$row['opis']."<br><br>
         				  <a href='konto.php?mk=3&zmo&album=".$_SESSION['album']."&zdjecie=".$row['id']."'><div id='btn3'>Zmień opis</div></a>
         				  <a href='konto.php?mk=3&usn&album=".$_SESSION['album']."&zdjecie=".$row['id']."'><div id='btn3'>Usuń zdjęcie</div></a>
         				  </div>
         				  <li id='center'><img style='width:180px ; height:180px; 'src='img/".$row['id_albumu']."/".$row['id'].".jpg' ></a></li></div>" ;	
         		}
         		echo '</ul>'; 
         	}
         	else
         	{
         
         		$pytanko=  "SELECT albumy.*, min(zdjecia.id) AS foto_id, uzytkownicy.login
							FROM albumy
								LEFT JOIN zdjecia ON albumy.id=zdjecia.id_albumu
								LEFT JOIN uzytkownicy ON albumy.id_uzytkownika=uzytkownicy.id
							WHERE zdjecia.zaakceptowane and uzytkownicy.id = ".$_SESSION['id']." 
							GROUP BY albumy.id";
         	
         	$wynik = mysqli_query($polaczenie,$pytanko);
         	echo '<div id="main">Wybierz album, żeby zmienić opis lub usunąć zdjęcie</div>';
         	echo "<ul class='album'>";
         	while($row = mysqli_fetch_array($wynik)) {
				echo "<div class='tooltip'>
				 	  <div class='tooltiptext'>".$row['tytul']."<br>". $row['login']."<br>".$row['data'] ."</div>
					   <li><a href='konto.php?mk=3&album=".$row['id']."' ><img style='border-radius: 10px; width:180px ; height:180px; 'src='img/".$row['id']."/".$row['foto_id'].".jpg' > </a></li>
					   </div>";	
         	}
         	echo "</ul>";
         	}
        }
    ?>
    
	<footer>
        <p>Grzegorz Jóźwiak IV TB</p>
    </footer>
   	</body>
</html>