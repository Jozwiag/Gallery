<?php
  session_start();
  include '../polaczenie.php';
  if(!isset($_SESSION['sort']))
  	$_SESSION['sort'] = 'nza';
  if(isset($_GET['sort']))
    $_SESSION['sort'] = $_GET['sort'];
?>
<!DOCTYPE html>

<html>

  <head>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="../css/css.css">
    <title>Panel administracyjny</title>
  </head>

  <body>

    <div id="menudiv">
      <ul class='menu'>
        <li><a class='menu' href="../galeria.php">Galeria</a></li>
        <li><a class='menu' href="../dodaj-album.php">Załóż album</a></li>
        <li><a class='menu' href="../dodaj-foto.php">Dodaj zdjęcie</a></li>
        <li><a class='menu' href="../top-foto.php">Najlepiej oceniane</a></li>
        <li><a class='menu' href="../nowe-foto.php">Najnowsze</a></li>
        <li><a class='menu' href="../konto.php">Moje konto</a></li>
        <li><a class='menu' href="../wyloguj.php">Wyloguj się</a></li>
		    <li><a class="menu" href="index.php">Panel administracyjny</a></li>
      </ul>
    </div>

    <div id="main">
      Panel administracyjny
    </div>

    <?php

      if($_SESSION['uprawnienia'] == 'administrator'){
        echo '
          <div id ="admincont">
            <a href="index.php?alb">
              <div id="admin">Albumy</div>
            </a>
            <a href="index.php?zdj">
              <div id="admin">Zdjęcia</div>
            </a>
            <a href="index.php?kom">
              <div id="admin">Komentarze</div>
            </a>
            <a href="index.php?uzy">
              <div id="admin">Użytkownicy</div>
            </a>
            <a href="../galeria.php">
              <div id="admin">Powrót</div>
            </a>
          </div>';
      }

      elseif($_SESSION['uprawnienia'] == 'moderator'){
        echo '
        <div id ="admincont">
          <a href="index.php?zdj">
            <div id="admin">Zdjęcia</div>
          </a>
          <a href="index.php?kom">
            <div id="admin">Komentarze</div>
          </a>
          <a href="../galeria.php">
            <div id="admin">Powrót</div>
          </a>
        </div>';
      }


      if(isset($_GET['alb'])){

            if(isset($_GET['zmt'])) {

              $_SESSION['idalbumu'] = $_GET['album'];
              echo '
                <form action="index.php?alb&zmt&album='.$_SESSION['idalbumu'].'" method="POST">
                <input id="form" value='.$_GET['tytul'].' placeholder="Nowy tytuł albumu" type="text" name="ntytul" required>
                <input id="submit" type="submit" value="Zmień tytuł albumu">   
                </form>';	
            
              if(isset($_POST['ntytul'])) {
                $nalbum = "UPDATE albumy SET tytul ='".$_POST['ntytul']."'
                          WHERE albumy.ID=".$_SESSION['idalbumu']."";
                $tytulalbumu = mysqli_query($polaczenie,$nalbum);
                echo "<script type='text/javascript'>alert('Pomyślnie zmieniono tytuł albumu');</script>";
                header('Refresh: 0; url=index.php?alb');
              }  

            }
            elseif(isset($_GET['usn'])) {		
              $_SESSION['idalbumu'] = $_GET['album'];
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
                header('Refresh: 0; url=index.php?alb');
            }

            else{
              $strona = (isset($_GET['strona'])) ? $_GET['strona'] : 1;

              $pytanko= "SELECT albumy.*, min(zdjecia.id) AS foto_id, uzytkownicy.login
              FROM albumy
                LEFT JOIN zdjecia ON albumy.id=zdjecia.id_albumu
                LEFT JOIN uzytkownicy ON albumy.id_uzytkownika=uzytkownicy.id
              GROUP BY albumy.id
              ORDER BY ".$_SESSION['sort']." DESC
              LIMIT ".(($strona - 1) * 20).",20";

              echo '<div id="top"><div id="sort">
              <b>Sortuj:</b>
              <a href="index.php?alb&sort=tytul"><div id="btn1">&nbspTytuł</div></a>
              <a href="index.php?alb&sort=data"><div id="btn1">Data dodania</div></a>
              <a href="index.php?alb&sort=login"><div id="btn1">Nick</div></a>
              <a href="index.php?alb&sort=nza"><div id="btn1">Liczba niezaakceptowanych zdjęć</div></a>
              </div>';

              $result = mysqli_query($polaczenie, "SELECT count(*) AS ile FROM albumy WHERE id=(SELECT id_albumu FROM zdjecia WHERE zaakceptowane = 1 GROUP BY id_albumu)");
              $r = mysqli_fetch_assoc($result);
              $stron = ceil(($r['ile'] / 20) +1);
              echo '<div id="page">';

              for($i= 1 ; $i<$stron ; $i++){	
                echo "<a href='index.php?alb&strona=$i'><div id='btn2'>Strona $i</div></a>";
              }

              echo '</div></div>';

              $wynik = mysqli_query($polaczenie,$pytanko);

              echo "<ul class='album'>";
              while($row = mysqli_fetch_array($wynik)) {
                if(isset($row['foto_id'])){
                  echo "
                    <div class='tooltip'>
                      <div class='tooltiptext'>
                        ".$row['tytul']."<br>
                        ".$row['login']."<br>
                        ".$row['data'] ."<br><br>
                        <a href='index.php?alb&zmt&tytul=".$row['tytul']."&album=".$row['id']."'><div id='btn3'>Zmień tytuł</div></a>
                        <a href='index.php?alb&usn&album=".$row['id']."'><div id='btn3'>Usuń album</div></a>
                        Liczba zdjęć czekających na akceptację:0
                      </div>
                    <li id='center'><img style='border-radius: 10px; width:180px ; height:180px; 'src='../img/".$row['id']."/".$row['foto_id'].".jpg'></li>
                    </div>";	
                  }
                else{
                  echo "
                    <div class='tooltip'>
                      <div class='tooltiptext'>
                      ".$row['tytul']."<br>
                      ".$row['login']."<br>
                      ".$row['data'] ."<br><br>
                      <a href='index.php?alb&zmt&tytul=".$row['tytul']."&album=".$row['id']."'><div id='btn3'>Zmień tytuł</div></a>
                      <a href='index.php?alb&usn&album=".$row['id']."'><div id='btn3'>Usuń album</div></a>
                      Liczba zdjęć czekających na akceptację:0
                      </div>	
                      <li id='center'><img style='border-radius: 10px; width:180px ; height:180px; 'src='../css/brak.png'></li>
                    </div>" ;
                }
              }
              echo "</ul>";

            }
      }
      if(isset($_GET['zdj'])){
        echo "
          <div id='container'>
            <a href='index.php?zdj&zdjalbumy'><div id='dane'>Wszystkie zdjęcia</div></a>
            <a href='index.php?zdj&zdjakc'><div id='dane'>Zdjecia do akceptacji</div></a>
          </div>";
        }

        if(isset($_GET['zdjakc'])){
          $zdj = mysqli_query($polaczenie, "SELECT * FROM zdjecia WHERE zaakceptowane=0");
          echo '<div id="main">
          Zdjęcia do akceptacji
          </div>';
          echo "<ul class='album'>";
          while($row = mysqli_fetch_array($zdj)) {
            echo "
                  <div class='tooltip'>
                    <div class='tooltiptext'>
                          <a href='index.php?zdj&zdjakc&usn&albumid=".$row['id_albumu']."&fotoid=".$row['id']."'><div id='btn3'>Usun zdjęcie</div></a>
                          <a href='index.php?zdj&zdjakc&akc&albumid=".$row['id_albumu']."&fotoid=".$row['id']."'><div id='btn3'>Zaakceptuj zdjęcie</div></a>
                        </div>
                      <li id='center'><img style='border-radius: 10px ;width:180px ; height:180px; 'src='../img/".$row['id_albumu']."/".$row['id'].".jpg'></li>
                      </div>";
          }
          echo "</ul>";

          if(isset($_GET['usn'])){
            $usunzdj="DELETE FROM zdjecia WHERE id=".$_GET['fotoid'];
            $ocenusun= "DELETE FROM zdjecia_oceny WHERE id_zdjecia=(SELECT id FROM zdjecia WHERE id=".$_GET['fotoid'].")";
            $komusun= "DELETE FROM zdjecia_komentarze WHERE id_zdjecia=(SELECT id FROM zdjecia WHERE id=".$_GET['fotoid'].")";
              
            $usunocenawynik= mysqli_query($polaczenie,$ocenusun);
            $usunkomwynik= mysqli_query($polaczenie,$komusun);
            $usunzdjwynik= mysqli_query($polaczenie,$usunzdj);
            $plik = "../img/".$_GET['albumid']."/".$_GET['fotoid'].".jpg";
            print_r($plik);
            unlink($plik);
            echo "<script type='text/javascript'>alert('Pomyślnie usunięto zdjęcie');</script>";
            header("Refresh:0; url=index.php?alb?zdjakc&albumid=".$_GET['albumid']);
          }
          if(isset($_GET['akc'])){
          $akc=mysqli_query($polaczenie, "UPDATE zdjecia SET zaakceptowane=1 WHERE id=".$_GET['fotoid']);
          header("Refresh:0; url=index.php?zdj&zdjakc");
          }
        }

        elseif(isset($_GET['zdjalbumy'])){

          if(isset($_GET['album'])){
            $zdjecia = mysqli_query($polaczenie, "	
                                                  SELECT zdjecia.* , uzytkownicy.login , albumy.tytul
                                                  FROM zdjecia 
                                                  LEFT JOIN albumy ON zdjecia.id_albumu = albumy.id
                                                  LEFT JOIN uzytkownicy ON albumy.id_uzytkownika = uzytkownicy.id
                                                  WHERE id_albumu =".$_GET['album']." 
                                                  ORDER BY data");
            echo '<div id="main">
                  Zdjęcia z albumu
                  </div>';

            echo "<ul class='album'>";
            while($row = mysqli_fetch_array($zdjecia)) {
              echo "<div class='tooltip'>
                      <div class='tooltiptext'>
                      <a href='index.php?zdj&zdjakc&usn&albumid=".$row['id_albumu']."&fotoid=".$row['id']."'><div id='btn3'>Usun zdjęcie</div></a>";
											if($row['zaakceptowane']==0){
                      echo "<a href='index.php?zdj&zdjakc&akc&albumid=".$row['id_albumu']."&fotoid=".$row['id']."'><div id='btn3'>Zaakceptuj zdjęcie</div></a>";
											}
                      echo "</div>
                    <li id='center'><img style='border-radius: 10px ;width:180px ; height:180px; 'src='../img/".$row['id_albumu']."/".$row['id'].".jpg' ></li></div>" ;	
            }
            echo '</ul>'; 
          }
          else{
            echo '<div id="main">
            Wybierz album
           </div>';
          $wynik= mysqli_query($polaczenie, "SELECT albumy.*, min(zdjecia.id) AS foto_id, uzytkownicy.login
                                                FROM albumy
                                                  LEFT JOIN zdjecia ON albumy.id=zdjecia.id_albumu
                                                  LEFT JOIN uzytkownicy ON albumy.id_uzytkownika=uzytkownicy.id
                                                GROUP BY albumy.id");
          if($wynik){
            echo "<ul class='album'>"; 
            while($row = mysqli_fetch_array($wynik)) {
                echo "<div class='tooltip'>
                  <div class='tooltiptext'>".$row['tytul']."<br>". $row['login']."<br>".$row['data'] ."</div>
                  <li id='center'><a href='index.php?zdj&zdjalbumy&album=".$row['id']."' ><img style='border-radius: 10px; width:180px ; height:180px; 'src='../img/".$row['id']."/".$row['foto_id'].".jpg' > </a></li>
                  </div>" ;	
              }
            echo "</ul>";
          }     
         }                                      
        }
      
      if(isset($_GET['kom'])){
        echo "
          <div id='container'>
            <a href='index.php?kom&wszkom'><div id='dane'>Wszystkie komentarze</div></a>
            <a href='index.php?kom&doakc'><div id='dane'>Komentarze do akceptacji</div></a>
          </div>";
        if(isset($_GET['wszkom'])){
					if(isset($_GET['edy'])){
						$_SESSION['id_zd']=$_GET['id'];
              echo '
							
                <form action="index.php?kom&wszkom&edy&id='.$_GET['id'].'" method="POST">
                <input id="form" value="'.$_GET['skom'].'"   placeholder="Edytuj komentarz" type="text" name="nkom" required>
                <input id="submit" type="submit" value="Zatwierdź">   
                </form>';	
            
              if(isset($_POST['nkom'])) {
                $tytulalbumu = mysqli_query($polaczenie, "UPDATE zdjecia_komentarze 
																													SET komentarz ='".$_POST['nkom']."'
																													WHERE id=".$_SESSION['id_zd']."");
													
                echo "<script type='text/javascript'>alert('Pomyślnie zmieniono komentarz');</script>";
                header('Refresh: 0; url=index.php?kom&wszkom');
							}
					}
					elseif(isset($_GET['zaa'])){
						$akc=mysqli_query($polaczenie, "UPDATE zdjecia_komentarze SET zaakceptowany=1 WHERE id=".$_GET['id']);
						header("Refresh:0; url=index.php?kom&wszkom");
					}
					elseif(isset($_GET['usun'])){
						$usu=mysqli_query($polaczenie,"DELETE FROM zdjecia_komentarze WHERE id=".$_GET['id']);
						header("Refresh:0; url=index.php?kom&wszkom");
					}
					else{

          $komy = mysqli_query($polaczenie, "SELECT zdjecia_komentarze.* , uzytkownicy.login FROM zdjecia_komentarze, uzytkownicy WHERE uzytkownicy.ID=zdjecia_komentarze.id_uzytkownika");

          echo "<div id='box'>";
          echo "<b><center>Komentarze</center></b><br>";

          while($row = mysqli_fetch_array($komy)) {
            echo "<div id='kom'><b>".$row['login']."</b>:&nbsp ".$row['komentarz']."<br>
            <a href='index.php?kom&wszkom&edy&skom=".$row['komentarz']."&id=".$row['id']."'><div id='btn'>Edytuj</div></a>&nbsp";

            if($_SESSION['uprawnienia'] == 'administrator')
              echo "<a href='index.php?kom&wszkom&usun&id=".$row['id']."'><div id='btn'>Usun</div></a>&nbsp";
            if($row['zaakceptowany']==0) echo "<a href='index.php?kom&wszkom&zaa&id=".$row['id']."'><div id='btn'>Zaakceptuj</div></a>&nbsp";
            echo "</div><br>";
          }

          echo "</div>";
					}
        }
        elseif(isset($_GET['doakc'])){
					
					if(isset($_GET['edy'])){
						$_SESSION['id_zd']=$_GET['id'];
              echo '
							
                <form action="index.php?kom&wszkom&edy&id='.$_GET['id'].'" method="POST">
                <input id="form" value="'.$_GET['skom'].'" placeholder="Edytuj komentarz" type="text" name="nkom" required>
                <input id="submit" type="submit" value="Zatwierdź">   
                </form>';	
            
              if(isset($_POST['nkom'])) {
                $tytulalbumu = mysqli_query($polaczenie, "UPDATE zdjecia_komentarze 
																													SET komentarz ='".$_POST['nkom']."'
																													WHERE id=".$_SESSION['id_zd']."");
													
                echo "<script type='text/javascript'>alert('Pomyślnie zmieniono komentarz');</script>";
                header('Refresh: 0; url=index.php?kom&doakc');
							}
					}
					elseif(isset($_GET['zaa'])){
						$akc=mysqli_query($polaczenie, "UPDATE zdjecia_komentarze SET zaakceptowany=1 WHERE id=".$_GET['id']);
						header("Refresh:0; url=index.php?kom&doakc");
					}
					elseif(isset($_GET['usun'])){
						$usu=mysqli_query($polaczenie,"DELETE FROM zdjecia_komentarze WHERE id=".$_GET['id']);
						header("Refresh:0; url=index.php?kom&doakc");
					}
					else{
          $komy = mysqli_query($polaczenie, "SELECT zdjecia_komentarze.* , uzytkownicy.login FROM zdjecia_komentarze, uzytkownicy WHERE uzytkownicy.ID=zdjecia_komentarze.id_uzytkownika and zaakceptowany = 0");

          echo "<div id='box'>";
          echo "<b><center>Komentarze</center></b><br>";

          while($row = mysqli_fetch_array($komy)) {
            echo "<div id='kom'><b>".$row['login']."</b>:&nbsp ".$row['komentarz']."<br>
            <a href='index.php?kom&doakc&edy&skom=".$row['komentarz']."&id=".$row['id']."''><div id='btn'>Edytuj</div></a>&nbsp";
            if($_SESSION['uprawnienia'] == 'administrator')
              echo "<a href='index.php?kom&doakc&usun&id=".$row['id']."''><div id='btn'>Usun</div></a>";
            echo" <a href='index.php?kom&doakc&zaa&id=".$row['id']."''><div id='btn'>Zaakceptuj</div></a>&nbsp";
            echo "</div><br>";
          }

          echo "</div>";
					}
        }

      }
      if(isset($_GET['uzy'])){
        
        echo "
        <div id='main'>
         Wybierz grupę użytkowników do wyświetlenia
        </div>
        <div style='text-align:center'>
        <a href='index.php?uzy&all'><div id='dane'>Wszyscy</div></a>
        </div><br>  
        <div id='container'>
          <a href='index.php?uzy&adm'><div id='dane'>Administratorzy</div></a>
          <a href='index.php?uzy&mod'><div id='dane'>Moderatorzy</div></a>
          <a href='index.php?uzy&use'><div id='dane'>Użytkownicy</div></a>
        </div>";

          if(isset($_GET['all'])){
            $users= mysqli_query($polaczenie , "SELECT * FROM uzytkownicy");
            
            echo "
                    <table class='tablecontainer'>
                      <thread>
                              <tr>
                                <td><h1>ID użytkownika</h1></td>
                                <td><h1>Login</h1></td>
                                <td><h1>Hasło</h1></td>
                                <td><h1>Email</h1></td>
                                <td><h1>Data rejestracji</h1></td>
                                <td><h1>Uprawnienia</h1></td>
                                <td><h1>Aktywny</h1></td>
                                <td><h1>Zmiana uprawnien</h1></td>
                                <td><h1>Zablokuj/Odblokuj</h1></td>
                                <td><h1>Usun uzytkownika</h1></td>
                              </tr>
                            </thread>";

            while($row = mysqli_fetch_array($users)){
              echo "<tbody>
                      <tr>
                      <td>".$row['ID']."</td>
                      <td>".$row['login']."</td>
                      <td>".$row['haslo']."</td>
                      <td>".$row['email']."</td>
                      <td>".$row['zarejestrowany']."</td>
                      <td>".$row['uprawnienia']."</td>
                      <td>".$row['aktywny']."</td>
                      <td><form action=zmiana.php?iduz=".$row['ID']." method='GET' >
                            <select name='upr'>
                              <option value='Uzytkownik'>Uzytkownik</option>
                              <option value='Moderator'>Moderator</option>
                              <option value='Administrator'>Administrator</option>
                            </select>
                            <input type='hidden' name='iduz' value='".$row['ID']."'>
                            <input type='hidden' name='str' value='all'>
                            <input type='submit'  name='submit' value='Zmien'>
                          </form></td>
                      <td>
                        <a href='zmiana.php?zab&str=all&iduz=".$row['ID']."'>
                         <div id='btn4'>Zablokuj</div>
                        </a>
                        <a href='zmiana.php?odb&str=all&iduz=".$row['ID']."'>
                        <div id='btn4'>Odblokuj</div>
                      </a>
                      </td>
                      <td>
                       <a href='zmiana.php?usn&str=all&iduz=".$row['ID']."'>
                          <div id='btn4'>Usun</div>
                       </a>
                      </td>
                    </tr>
                    </tbody>";
            }

            echo "</table>";

          }
          if(isset($_GET['adm'])){
            $users= mysqli_query($polaczenie , "SELECT * FROM uzytkownicy WHERE uprawnienia = 'administrator'");
            echo "
                    <table class='tablecontainer'>
                      <thread>
                              <tr>
                                <td><h1>ID użytkownika</h1></td>
                                <td><h1>Login</h1></td>
                                <td><h1>Hasło</h1></td>
                                <td><h1>Email</h1></td>
                                <td><h1>Data rejestracji</h1></td>
                                <td><h1>Uprawnienia</h1></td>
                                <td><h1>Aktywny</h1></td>
                                <td><h1>Zmiana uprawnien</h1></td>
                                <td><h1>Zablokuj/Odblokuj</h1></td>
                                <td><h1>Usun uzytkownika</h1></td>
                              </tr>
                            </thread>";

            while($row = mysqli_fetch_array($users)){
              echo "<tbody>
                      <tr>
                      <td>".$row['ID']."</td>
                      <td>".$row['login']."</td>
                      <td>".$row['haslo']."</td>
                      <td>".$row['email']."</td>
                      <td>".$row['zarejestrowany']."</td>
                      <td>".$row['uprawnienia']."</td>
                      <td>".$row['aktywny']."</td>
                      <td><form action=zmiana.php?iduz=".$row['ID']." method='GET' >
                            <select name='upr'>
                              <option value='Uzytkownik'>Uzytkownik</option>
                              <option value='Moderator'>Moderator</option>
                              <option value='Administrator'>Administrator</option>
                            </select>
                            <input type='hidden' name='iduz' value='".$row['ID']."'>
                            <input type='hidden' name='str' value='adm'>
                            <input type='submit'  name='submit' value='Zmien'>
                          </form></td>
                      <td>
                        <a href='zmiana.php?zab&str=adm&iduz=".$row['ID']."'>
                         <div id='btn4'>Zablokuj</div>
                        </a>
                        <a href='zmiana.php?odb&str=adm&iduz=".$row['ID']."'>
                        <div id='btn4'>Odblokuj</div>
                      </a>
                      </td>
                      <td>
                       <a href='zmiana.php?usn&str=adm&iduz=".$row['ID']."'>
                          <div id='btn4'>Usun</div>
                       </a>
                      </td>
                    </tr>
                    </tbody>";
            }

            echo "</table>";
          }
          if(isset($_GET['mod'])){
            $users= mysqli_query($polaczenie , "SELECT * FROM uzytkownicy WHERE uprawnienia = 'moderator'");
            echo "
                    <table class='tablecontainer'>
                      <thread>
                              <tr>
                                <td><h1>ID użytkownika</h1></td>
                                <td><h1>Login</h1></td>
                                <td><h1>Hasło</h1></td>
                                <td><h1>Email</h1></td>
                                <td><h1>Data rejestracji</h1></td>
                                <td><h1>Uprawnienia</h1></td>
                                <td><h1>Aktywny</h1></td>
                                <td><h1>Zmiana uprawnien</h1></td>
                                <td><h1>Zablokuj/Odblokuj</h1></td>
                                <td><h1>Usun uzytkownika</h1></td>
                              </tr>
                            </thread>";

            while($row = mysqli_fetch_array($users)){
              echo "<tbody>
                      <tr>
                      <td>".$row['ID']."</td>
                      <td>".$row['login']."</td>
                      <td>".$row['haslo']."</td>
                      <td>".$row['email']."</td>
                      <td>".$row['zarejestrowany']."</td>
                      <td>".$row['uprawnienia']."</td>
                      <td>".$row['aktywny']."</td>
                      <td><form action=zmiana.php?iduz=".$row['ID']." method='GET' >
                            <select name='upr'>
                              <option value='Uzytkownik'>Uzytkownik</option>
                              <option value='Moderator'>Moderator</option>
                              <option value='Administrator'>Administrator</option>
                            </select>
                            <input type='hidden' name='iduz' value='".$row['ID']."'>
                            <input type='hidden' name='str' value='mod'>
                            <input type='submit'  name='submit' value='Zmien'>
                          </form></td>
                      <td>
                        <a href='zmiana.php?zab&str=mod&iduz=".$row['ID']."'>
                         <div id='btn4'>Zablokuj</div>
                        </a>
                        <a href='zmiana.php?odb&str=mod&iduz=".$row['ID']."'>
                        <div id='btn4'>Odblokuj</div>
                      </a>
                      </td>
                      <td>
                       <a href='zmiana.php?usn&str=mod&iduz=".$row['ID']."'>
                          <div id='btn4'>Usun</div>
                       </a>
                      </td>
                    </tr>
                    </tbody>";
            }

            echo "</table>";
          }
          if(isset($_GET['use'])){
            $users= mysqli_query($polaczenie , "SELECT * FROM uzytkownicy WHERE uprawnienia = 'uzytkownik'");
            echo "
                    <table class='tablecontainer'>
                      <thread>
                              <tr>
                                <td><h1>ID użytkownika</h1></td>
                                <td><h1>Login</h1></td>
                                <td><h1>Hasło</h1></td>
                                <td><h1>Email</h1></td>
                                <td><h1>Data rejestracji</h1></td>
                                <td><h1>Uprawnienia</h1></td>
                                <td><h1>Aktywny</h1></td>
                                <td><h1>Zmiana uprawnien</h1></td>
                                <td><h1>Zablokuj/Odblokuj</h1></td>
                                <td><h1>Usun uzytkownika</h1></td>
                              </tr>
                            </thread>";

            while($row = mysqli_fetch_array($users)){
              echo "<tbody>
                      <tr>
                      <td>".$row['ID']."</td>
                      <td>".$row['login']."</td>
                      <td>".$row['haslo']."</td>
                      <td>".$row['email']."</td>
                      <td>".$row['zarejestrowany']."</td>
                      <td>".$row['uprawnienia']."</td>
                      <td>".$row['aktywny']."</td>
                      <td><form action=zmiana.php?iduz=".$row['ID']." method='GET' >
                            <select name='upr'>
                              <option value='Uzytkownik'>Uzytkownik</option>
                              <option value='Moderator'>Moderator</option>
                              <option value='Administrator'>Administrator</option>
                            </select>
                            <input type='hidden' name='iduz' value='".$row['ID']."'>
                            <input type='hidden' name='str' value='use'>
                            <input type='submit'  name='submit' value='Zmien'>
                          </form></td>
                      <td>
                        <a href='zmiana.php?zab&str=use&iduz=".$row['ID']."'>
                         <div id='btn4'>Zablokuj</div>
                        </a>
                        <a href='zmiana.php?odb&str=use&iduz=".$row['ID']."'>
                        <div id='btn4'>Odblokuj</div>
                      </a>
                      </td>
                      <td>
                       <a href='zmiana.php?usn&str=use&iduz=".$row['ID']."'>
                          <div id='btn4'>Usun</div>
                       </a>
                      </td>
                    </tr>
                    </tbody>";
            }

            echo "</table>";
          }
        }
      

    ?>
  <footer>
    <p>Grzegorz Jóźwiak IV TB</p>
  </footer>
  </body>
</html>