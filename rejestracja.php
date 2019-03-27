<?php
session_start();
include 'polaczenie.php';
$login = $_POST['login']; 
$haslo = $_POST['haslo']; 
$haslo_p = $_POST['haslo_p']; 
$email = $_POST['email']; 
$data=date("Y-m-d");
$_SESSION['login'] = $_POST['login'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['haslo'] = $_POST['haslo'];
$zapytanie = mysqli_query($polaczenie, "SELECT * FROM uzytkownicy WHERE login = '$login' ");

if (mysqli_num_rows($zapytanie) ==0 ){
	if($haslo==$haslo_p) {
		if($login and $haslo and $email) { 
				$ins = mysqli_query($polaczenie, "INSERT INTO uzytkownicy SET login='$login', haslo=md5('$haslo'), email='$email',zarejestrowany='$data',uprawnienia='uzytkownik' ,aktywny='1'"); 
				$zapytanie = mysqli_query($polaczenie, "SELECT * FROM uzytkownicy WHERE login = '$login' ");
				$dane = mysqli_fetch_assoc($zapytanie);
				$_SESSION['zalogowany'] = true;
				$_SESSION['id'] = $dane['ID'];
				$_SESSION['uprawnienia'] = $dane['uprawnienia'];
				header("Location: rejestracja-ok.php");
		} 
	}
	else{ 
		echo "<script type='text/javascript'>alert('Hasła nie są identyczne, zostaniesz przekierowany do formularza');</script>";
		header('Refresh: 0; url=index.php');
	}
}
else{
	echo "<script type='text/javascript'>alert('Taki login jest zajęty, zostaniesz przekierowany do formularza');</script>";
	header('Refresh: 0; url=index.php');
}
mysqli_close($polaczenie); 
?>

