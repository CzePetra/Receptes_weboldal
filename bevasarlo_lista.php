<!DOCTYPE html>
<html>
  <?php
  session_start();
  $_SESSION['oldal']='Bevásárló lista generálás';
  $_SESSION['atiranyitott_oldal']='otthon'; 
  include 'menu.php';
  include 'db.php';
  if(!isset($_SESSION['bejelentkezve']))
  {
      header("Location: bejelentkezes.php?tema=$tema");     
  }
  else
  {
    if($_SESSION['bejelentkezve']==0)
    header("Location: bejelentkezes.php?tema=$tema");
  }
  $id=$_SESSION['id']; 
  if($id==0) die('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Hiba történt, jelentkezzen be újra!</span>');
  ?>
  <body class="<?php echo $tema?>">
    <div class="text-center">
      <nav class="navbar navbar-expand-lg navbar-<?php echo $tema?> bg-<?php echo $tema?>">
        <div class="navbar-nav">
        <a class="nav-item nav-link" href="kijelentkezes.php?tema=<?php echo $tema?>">Kijelentkezés</a>
        </div>
      </nav>
      <br/>
      <h4 style="width: 85%; margin: 0 auto;">
      <br><br> 
        <?php
        echo'<form action="bevasarlo_lista.php?tema='.$tema.'" method="post">';

        $link=getDb();
        $receptek=$link->query("SELECT Recept.nev, Recept.recept_id From Recept inner join Recepteskonyv on (Recept.recept_id=Recepteskonyv.Recept_id) inner join Felhasznalo on(Recepteskonyv.Felhasznalo_id=$id) where Felhasznalo.felhasznalo_id=$id ") or die(mysqli_error($link));
        $lista=mysqli_num_rows($receptek);
        mysqli_close($link);
        if($lista>0)
        {
          $link=getDb();
          echo '<select name="receptek" class="btn btn-light"  style="width: 500px">
          <option value="">A recept kiválasztása a bevásárló listához</option>';

          while($row=mysqli_fetch_assoc($receptek))
          {
            $szam=$row['recept_id'];
            $szoveg=$row['nev'];
            echo '<option value="'.$szam.'">'.$szoveg.'</option>'; 
          }
          echo '</select>      
          <input type="submit" class="btn btn-'.$tema.'" style="color: white; background-color:purple" value="Bevásárló lista készítése" name="felvett">
          </form>';
          mysqli_close($link);
        }
        else echo('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey; color:white">Önnek nincs még egyetlen kedvenc receptje sem, először válasszon ki legalább egyet, hogy elkészíthesse a bevásárló listáját!</span>');
      
        if(isset($_POST['felvett']) and $_POST['receptek']!="")
        {
          $recept_id=$_POST['receptek'];
          $link=getDb();
          $kivalasztott=$link->query("SELECT Osszetevok_listaja.Hozzavalo_id as szukseges_id from Osszetevok_listaja left join Otthon on(Otthon.hozzavalo_id=Osszetevok_listaja.Hozzavalo_id and Otthon.felhasznalo_id=$id) where Otthon.hozzavalo_id is null and Osszetevok_listaja.Recept_id=$recept_id");
          if(mysqli_num_rows($kivalasztott)==0)
            echo('<br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue">Minden szükséges hozzávalója megvan :) </span>');
          else
            echo('<span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue">A következő hozzávalókat kell beszereznie: </span>');
          while($row=mysqli_fetch_assoc($kivalasztott))
          {
            $szukseges_id=$row['szukseges_id'];
            $nev_megszerzes=$link->query("SELECT nev from Hozzavalok where hozzavalo_id=$szukseges_id");
            $row_nev=mysqli_fetch_assoc($nev_megszerzes);
            echo ('<span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue">'.$row_nev['nev'].', </span>');
          }
          mysqli_close($link);
        }
        else if(isset($_POST['felvett']) and $_POST['receptek']=="")
          echo('<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey; color:white">Kérem válasszon a legördülő listából!</span>');

        ?>
      </h4> 
    </div>
  </body>
</html>
