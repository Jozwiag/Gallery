<?php
session_start();
include 'polaczenie.php';
$ocenusun= "DELETE FROM zdjecia_oceny WHERE id_zdjecia=(SELECT id FROM zdjecia WHERE id_albumu =".$_SESSION['idalbumu'].")";
$komusun= "DELETE FROM zdjecia_komentarze WHERE id_zdjecia=(SELECT id FROM zdjecia WHERE id_albumu =".$_SESSION['idalbumu'].")";


$zdjusun= "DELETE FROM zdjecia WHERE id_albumu =".$_SESSION['idalbumu']."";
$alusun= "DELETE FROM albumy WHERE id =".$_SESSION['idalbumu']."";

$ocenwynik = mysqli_query($polaczenie,$ocenusun);
$komwynik = mysqli_query($polaczenie,$komusun);
$alwynik = mysqli_query($polaczenie,$alusun);
$zdjwynik = mysqli_query($polaczenie,$zdjusun);




$folder= "img/".$_SESSION['idalbumu']."/";
$files= glob($folder . '/*');

foreach($files as $file){
	if(is_file($file)){
		unlink($file);
	}
}
$path = "img/".$_SESSION['idalbumu'];
rmdir($path);
header('Refresh: 0; url=konto.php?mk=2');
?>