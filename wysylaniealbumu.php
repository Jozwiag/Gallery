<?php
session_start();
include 'polaczenie.php';
$album = $_POST['album'];
$data  = date("Y-m-d G-i-s");
$id    = $_SESSION['id'];

$log = mysqli_query($polaczenie, "SELECT * FROM albumy WHERE tytul = '$album' ");

if (strlen($album) < 101 && strlen($album) > 2) 
{
	if ($album) 
	{
		$ins = mysqli_query($polaczenie, "INSERT INTO albumy SET tytul='" . trim($album) . "', data='$data', id_uzytkownika ='$id' ");
    if ($ins) 
		{
			$id = mysqli_insert_id($polaczenie);
      mkdir("IMG/" . $id);
      echo "<script type='text/javascript'>alert('Album został dodany poprawnie');</script>";
      header('Refresh:0; url=dodaj-foto.php?album='.$id.'');
    }
		else
		{
      echo "<script type='text/javascript'>alert('Nie udało się dodać nowego albumu');</script>";
		  header('Refresh:0; url=dodaj-album.php');
		}
	}
} 
else 
{
	echo "<script type='text/javascript'>alert('Nazwa musi zawierać od 3 do 100 znaków, bez początkowych i końcowych spacji');</script>";
  header('Refresh: 0; url=dodaj-album.php');
}
?>