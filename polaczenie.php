<?php
    $polaczenie = mysqli_connect("localhost","root","","jozwiak_4tb");
   
		if (mysqli_connect_errno()) {
		 echo "Błąd połączenia nr: " . mysqli_connect_errno();
		 echo "Opis błędu: " . mysqli_connect_error();
		 exit();
		}
		
		mysqli_query($polaczenie, 'SET NAMES utf8');
		mysqli_query($polaczenie, 'SET CHARACTER SET utf8');
		mysqli_query($polaczenie, "SET collation_connection = utf8_polish_ci");
?>