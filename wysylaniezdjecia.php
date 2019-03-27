<?php
session_start();
include 'polaczenie.php';

$data= date("Y-m-d G-i-s");
$opis= $_POST['opis'];


if(isset($_FILES["foto"])){ 
	if ($_FILES["foto"]["type"]=='image/jpeg' || $_FILES["foto"]["type"]=='image/png')
	{
		$ins = mysqli_query($polaczenie, "INSERT INTO zdjecia SET opis='$opis', id_albumu=".$_SESSION['idalbumu'].", data='$data',zaakceptowane='0' "); 
		move_uploaded_file($_FILES["foto"]["tmp_name"],"img/".$_SESSION['idalbumu']."/".  mysqli_insert_id($polaczenie).".jpg");
		if($ins) echo "<script type='text/javascript'>alert('Rekord został dodany poprawnie');</script>";
		else echo "<script type='text/javascript'>alert('Błąd nie udało się dodać nowego rekordu');</script>";
		header("Refresh:0; url=dodaj-foto.php?album=".$_SESSION['idalbumu']."");
	 }
	 else echo "<script type='text/javascript'>alert('Zdjęcie musi mieć rozszerzenie `jpg` albo `png` ');</script>";
	 header("Refresh:0; url=dodaj-foto.php?album=".$_SESSION['idalbumu']."");
}
?>