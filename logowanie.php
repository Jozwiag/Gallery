<?php
session_start();
include 'polaczenie.php';
$login = $_POST['login']; 
$haslo = $_POST['haslo']; 
$zapytanie = mysqli_query($polaczenie, "SELECT * FROM uzytkownicy WHERE login = '$login' and haslo = md5('$haslo') ");

if (mysqli_num_rows($zapytanie) == 1) {
		$dane = mysqli_fetch_assoc($zapytanie);
		if ($dane['aktywny'] ==1) {
			$_SESSION['zalogowany'] = true;
			$_SESSION['login'] = $login;
			$_SESSION['haslo'] = $haslo;
			$_SESSION['id'] = $dane['ID'];
			$_SESSION['email'] = $dane['email'];
			$_SESSION['uprawnienia'] = $dane['uprawnienia'];
			header("Location: galeria.php");
			}
	  else{
			echo "<script type='text/javascript'>alert('Twoje konto zostało zablokowane! Skontaktuj się z administratorem.');</script>";
			header('Refresh: 0; url=index.php');
		}
}
else{ 
	echo "<script type='text/javascript'>alert('Wpisano złe dane, zostaniesz przeniesiony do formularza');</script>";
	header('Refresh: 0; url=index.php');
}
mysqli_close($polaczenie); 
?>