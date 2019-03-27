<div id="menudiv">
<ul class='menu'>
  <li><a class='menu' href="galeria.php">Galeria</a></li>
  <li><a class='menu' href="dodaj-album.php">Załóż album</a></li>
  <li><a class='menu' href="dodaj-foto.php">Dodaj zdjęcie</a></li>
  <li><a class='menu' href="top-foto.php">Najlepiej oceniane</a></li>
  <li><a class='menu' href="nowe-foto.php">Najnowsze</a></li>
  <li><a class='menu' href="konto.php">Moje konto</a></li>
  <li><a class='menu' href="wyloguj.php">Wyloguj się</a></li>
	<?php 
	if($_SESSION['uprawnienia'] == 'administrator' || $_SESSION['uprawnienia'] == 'moderator') 
	{
		echo '<li><a  class="menu" href="admin/index.php">Panel administracyjny</a></li>';
	}
  ?>
</ul>
</div>