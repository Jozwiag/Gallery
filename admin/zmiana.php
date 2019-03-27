<?php
	session_start();
    include '../polaczenie.php';
    
	if(isset($_GET['upr'])){
        $zmiana= mysqli_query($polaczenie, "UPDATE uzytkownicy SET uprawnienia = '".$_GET['upr']."'
                                            WHERE id=".$_GET['iduz']);
        
    }
    if(isset($_GET['zab'])){
        $zmiana= mysqli_query($polaczenie, "UPDATE uzytkownicy SET aktywny = 0
                                            WHERE id=".$_GET['iduz']);
    }
    if(isset($_GET['odb'])){
        $zmiana= mysqli_query($polaczenie, "UPDATE uzytkownicy SET aktywny = 1
                                            WHERE id=".$_GET['iduz']);
    }
    if(isset($_GET['usn'])){
        $delkom = mysqli_query($polaczenie , "DELETE FROM zdjecia_komentarze 
                                              WHERE id_uzytkownika = ".$_GET['iduz']);
        $delocen = mysqli_query($polaczenie , "DELETE FROM zdjecia_oceny 
                                               WHERE id_uzytkownika = ".$_GET['iduz']);

        $albumy = mysqli_query($polaczenie, "SELECT id FROM albumy WHERE id_uzytkownika = ".$_GET['iduz']);

        while ($row= mysqli_fetch_assoc($albumy)){
            $folder= "../img/".$row['id']."/";
            $files= glob($folder . '/*');
            foreach($files as $file){
                if(is_file($file)){
                    unlink($file);
                }
            }
            $path = "../img/".$row['id'];
            rmdir($path);
        }


        $delzdj = mysqli_query($polaczenie , "DELETE FROM zdjecia 
                                              WHERE id_albumu = (SELECT id FROM albumy WHERE id_uzytkownika =".$_GET['iduz']." ) ");
        $delalb = mysqli_query($polaczenie , "DELETE FROM albumy 
                                              WHERE id_uzytkownika = ".$_GET['iduz']);
        $delalb = mysqli_query($polaczenie , "DELETE FROM uzytkownicy 
                                              WHERE id= ".$_GET['iduz']);                                           
    }
	header('Location:index.php?uzy&'.$_GET['str']);
?>