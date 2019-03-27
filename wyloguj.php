<?php
session_start();

if (!isSet($_SESSION['zalogowany']))
	{
	$komunikat = "Nie byłeś zalogowany!!!";
	}
  else
	{
	unset($_SESSION['zalogowany']);
	$komunikat = "Wylogowanie prawidłowe!";
	}

session_destroy();
header('Refresh: 0; url=index.php');
?>

<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="css/css.css">
<title>Wylogowywanie</title>
</head>
<body>
<?php

if (isSet($_SESSION['zalogowany'])) include 'menuzalogowany.php';

  else include 'menuniezalogowany.php';

?>
<footer>
  <p>Grzegorz Jóźwiak IV TB</p>
</footer>
</body>
</html>