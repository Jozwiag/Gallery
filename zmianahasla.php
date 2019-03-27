<?php
session_start();
include 'polaczenie.php';
$hasla = mysqli_query($polaczenie,"SELECT uzytkownicy.haslo FROM uzytkownicy WHERE uzytkownicy.ID='".$_SESSION['id']."'");
$sthaslo = mysqli_fetch_array($hasla);

if(md5($_GET['haslo']) == $sthaslo['haslo'])
{
	if($_GET['nhaslo'] == $_GET['nhaslo2'])
	{
		$zapytanie = mysqli_query($polaczenie,"UPDATE uzytkownicy SET haslo=md5('".$_GET['nhaslo']."')
		WHERE uzytkownicy.ID='".$_SESSION['id']."'");
		echo "<script type='text/javascript'>alert('Hasło zostało pomyślnie zmienione');</script>";
		header('Refresh: 0; url=galeria.php');
	}
	else{ 
		echo "<script type='text/javascript'>alert('Nowe hasła się nie zgadzają, zostaniesz przekierowany do formularza');</script>";
		header('Refresh: 0; url=konto.php?mk=1&md=1');
	}
}
else{ 
		echo "<script type='text/javascript'>alert('Obecne hasło się nie zgadza, zostaniesz przekierowany do formularza');</script>";
		header('Refresh: 0; url=konto.php?mk=1&md=1');
	}
?>
