<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="css/css.css">
<title>Strona główna</title>
</head>
<body>

<?php
if(isSet($_SESSION['zalogowany']))
include 'menuzalogowany.php';
else
include 'menuniezalogowany.php';

?>
<div id="main">
Logowanie
</div>
<form action="logowanie.php" method="POST">
    <input id="form" placeholder="Login" <?php if(isset($_SESSION['login'])) echo "value = ".$_SESSION['login']."" ?> type="text" name="login" required>
    <br>
    <input id="form" placeholder="Hasło" type="password" name="haslo" required>
    <br>
    <input id="submit" type="submit" value="Zaloguj">   
  </form>
	
	
	
<div id="main">
Rejestracja
</div>
<form action="rejestracja.php" method="POST">
    <input id="form" placeholder="Login" <?php if(isset($_SESSION['login'])) echo "value = ".$_SESSION['login']."" ?> type="text" name="login" pattern="[A-Za-z0-9]{6,20}" title="Tylko litery i liczby, od 6 do 20 znaków" required >
    <br>
    <input id="form" placeholder="Hasło" type="password" name="haslo" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}" title="Conajmniej 1 duża litera, 1 mała litera i 1 cyfra, od 6 do 20 znaków" required>
    <br>
    <input id="form" placeholder="Potwierdź hasło" type="password" name="haslo_p" input  required>
    <br>
    <input id="form" placeholder="E-mail" <?php if(isset($_SESSION['email'])) echo "value = ".$_SESSION['email']."" ?> type="email" name="email" input pattern=".{0,128}" required>
    <br>
    <input id="submit" type="submit" value="Zajerestruj">   
  </form>
<footer>
  <p>Grzegorz Jóźwiak IV TB</p>
</footer>	
</body>
</html>