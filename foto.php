<?php
session_start();
include 'polaczenie.php';

?>
<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="css/css.css">
<title>Zdjęcie</title>
</head>
<body>

<?php
if (isSet($_SESSION['zalogowany']))
    include 'menuzalogowany.php';
else
    include 'menuniezalogowany.php';

 if (isSet($_GET['album'])) $_SESSION['album'] = $_GET['album'];

$pytanko = "    
SELECT zdjecia.* , uzytkownicy.login , albumy.tytul
FROM zdjecia 
LEFT JOIN albumy ON zdjecia.id_albumu = albumy.id
LEFT JOIN uzytkownicy ON albumy.id_uzytkownika = uzytkownicy.id
WHERE zdjecia.id=".$_GET['zdjecie']." and zaakceptowane = 1 ";

$wynik = mysqli_query($polaczenie, $pytanko);
$row   = mysqli_fetch_array($wynik);

echo '<div id="top">
<a href="album.php?album='.$_SESSION["album"].'"><div id="powrot">Powrót</div></a></div>';

echo "<div id='box1'>
Tytuł albumu:&nbsp<i>".$row['tytul']."</i><br>
Autor:&nbsp<i>       ".$row['login']."</i><br>
Data dodania:&nbsp<i>".$row['data']."</i><br>";
if(($row['opis'])==''){
    echo "Brak opisu";
}
else {
    echo "Opis:&nbsp<i>".$row['opis']."</i><br>";
}
echo "</div>";
echo "<img class='center' src='img/" . $_SESSION['album'] . "/" . $row['id'] . ".jpg'>";



$pytankodwa = mysqli_query($polaczenie, " SELECT AVG(ocena) as ocena, count(*) as ile FROM zdjecia_oceny WHERE id_zdjecia = '" . $_GET['zdjecie'] . "' ");
$row        = mysqli_fetch_assoc($pytankodwa);

if (isset($_GET['ocena'])) {
    if (!($_GET['ocena'] === "fail")) {
        mysqli_query($polaczenie, "INSERT INTO zdjecia_oceny SET id_zdjecia=".$_GET['zdjecie']." , id_uzytkownika=".$_SESSION['id']." , ocena=". $_GET['ocena']);
        header("Location:foto.php?zdjecie=".$_GET['zdjecie']);
    } 
    else {
        echo "Nie dałeś oceny";
    }
}
if (isset($_POST['kom'])) {
    if (!($_POST['kom'] === "fail")) {
        mysqli_query($polaczenie, " INSERT INTO zdjecia_komentarze SET id_zdjecia='" . $_GET['zdjecie'] . "', id_uzytkownika='" . $_SESSION['id'] . "',data=now(), komentarz='" . $_POST['kom'] . "' ");
        header("Refresh:0");
    } else {
        echo "Nie napisałeś komentarza";
    }
}

$pytankotrzy = mysqli_query($polaczenie, " SELECT * FROM zdjecia_oceny WHERE id_zdjecia='" . $_GET['zdjecie'] . "' AND id_uzytkownika='" . $_SESSION['id'] . "' ");
$ile         = mysqli_num_rows($pytankotrzy);
if ($ile < 1 && isset($_SESSION['zalogowany'])) {
    echo '
        <form action="foto.php?zdjecie=' . $_GET['zdjecie'] . '  method="POST">
        <input  type="hidden" value="' . $_GET['zdjecie'] . '" name="zdjecie">
        <div  id="center">
            <select name="ocena" >
                <option value="fail">Oceń</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
                <option>7</option>
                <option>8</option>
                <option>9</option>
                <option>10</option>
            </select></br></br>
        </div>
            <input type="submit" id="submit" name="Oceń" value="Oceń">
         
        </form> ';
} elseif ($ile > 1 && isset($_SESSION['zalogowany'])) {
    echo "Głosowałeś już";
} elseif (!isset($_SESSION['zalogowany'])) {
    echo "<br>Musisz się zalogować aby głosować";
}
if(isset($row['ocena'])){
    echo '<center><p>Ocena zdjęcia: ' . round($row['ocena'], 1) . ' </br> Ocenione przez: ' . $row['ile'] . ' użytkowników</center><br>';
}
else echo '<Center>To zdjecie nie ma jeszcze ocen</center><br>';
$pytaniekom = mysqli_query($polaczenie, " SELECT zdjecia_komentarze.* , uzytkownicy.login FROM  zdjecia_komentarze 
																					LEFT JOIN uzytkownicy ON zdjecia_komentarze.id_uzytkownika = uzytkownicy.ID 
																					WHERE zaakceptowany=1 and id_zdjecia ='" . $_GET['zdjecie'] . "'");
																					
echo "<div id='box'>";
    echo '<b><center>Komentarze</center></b><br>'																													;
    while($kom = mysqli_fetch_array($pytaniekom)) {
        echo "<div id='kom'><b>".$kom['login']."</b>:&nbsp ".$kom['komentarz']."</div>" ;	
    };

echo "</div>";


if (isset($_SESSION['zalogowany'])) {
echo'<form action="foto.php?zdjecie='.$_GET['zdjecie'].'" method="POST">
    <input id="form" placeholder="Dodaj komentarz" type="text" name="kom" required>
    <br>
    <input id="submit" type="submit" value="Skomentuj">   
  </form>';
}
?>

<footer>
  <p>Grzegorz Jóźwiak IV TB</p>
</footer>
</body>
</html>