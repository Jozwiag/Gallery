<?php
session_start();
include 'polaczenie.php';
$hasla = mysqli_query($polaczenie,"SELECT uzytkownicy.haslo FROM uzytkownicy WHERE uzytkownicy.ID='".$_SESSION['id']."'");
$sthaslo = mysqli_fetch_array($hasla);

if(md5($_GET['haslo']) == $sthaslo['haslo'])
{
	$zapytanie = mysqli_query($polaczenie,"UPDATE uzytkownicy SET email='".$_GET['email']."'
	WHERE uzytkownicy.ID='".$_SESSION['id']."'");
	echo "<script type='text/javascript'>alert('Email pomyślnie zmieniony');</script>";
	header('Refresh: 0; url=galeria.php');
}
else{ 
		echo "<script type='text/javascript'>alert('Hasło się nie zgadza, zostaniesz przekierowany do formularza');</script>";
		header('Refresh: 0; url=konto.php?mk=1&md=2');
	}
?>
