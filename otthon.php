<!DOCTYPE html>
<html>
  <?php 
    session_start();
    $_SESSION['oldal']='Otthon';
    $_SESSION['rogzult_receptek']=0;
    $_SESSION['rogzult_hozzavalo']=0;
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
      if($id!==0)
      {
        $link=getDB();
        $nev=$link->query("SELECT nev from Felhasznalo where felhasznalo_id='{$id}'")->fetch_object()->nev;
        mysqli_close($link);
      } 
      else die('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Hiba történt, jelentkezzen be újra!</span>');
        
    ?>
  <head>
    <title>Otthon</title>
  </head>
  <body>
    <div class="text-center">    
      <nav class="navbar navbar-expand-lg navbar-<?php echo $tema?> bg-<?php echo $tema?>">
        <div class="navbar-nav">
          <form class="form-inline my-2 my-lg-0" method="post">

            <label class="form-check-label" for="alapanyag">Hozzávalók</label> 
            &nbsp; <!-- szebb kinézet miatt, hogy ne csússzanak egybe-->
            <input class="form-check form-check-inline" type="radio" name="valasztas" value="1"  >

            <label class="form-check-label" for="recept">Kedvenc receptek</label> 
            &nbsp; <!-- szebb kinézet miatt, hogy ne csússzanak egybe-->
            <input class="form-check form-check-inline" type="radio" name="valasztas" value="2"  >

            <input class="form-control mr-sm-2"  name='cel' type="text" placeholder="Keresés" aria-label="Keresés">
            <button class="btn btn-<?= $tema?>" type="submit">Keresés</button>

          </form>
        <a class="nav-item nav-link" href="bevasarlo_lista.php?tema=<?php echo $tema?>">Bevásárló lista</a>
        <a class="nav-item nav-link" href="kedvencek_kivalasztasa.php?tema=<?php echo $tema?>">Kedvenc receptek kiválasztása</a>
        <a class="nav-item nav-link" href="feltoltes.php?tema=<?php echo $tema?>">Árufeltöltés</a>
        <a class="nav-item nav-link" href="kijelentkezes.php?tema=<?php echo $tema?>">Kijelentkezés</a>
        </div>
      </nav>
      <h1>
        <p>
          <?php
          if(!isset($_POST['cel']))
          {echo('Üdvözöljük '); echo $nev; echo('!'); }
          ?>
        </p>
      </h1>
      <h4 style="width: 85%; margin: 0 auto;">
        <table class="table table-striped table-<?php echo $tema?> text-center " >
          <?php 
          if(!isset($_POST['valasztas'])) echo("Kérem válasszon a lehetőségek közül!");
          else
          {
            if(isset($_POST['cel']) and $_POST['valasztas']==2) //kedvenc receptek kilistázása keresés alapján
            {
              $link=getDB();
              $kereses=mysqli_real_escape_string($link,$_POST['cel']);
              
              $cel=$link->query("SELECT Recept.nev, time_min, nehezsegi_szint, megjegyzest, Recept.recept_id From Recept inner join Recepteskonyv on (Recept.recept_id=Recepteskonyv.Recept_id) inner join Felhasznalo on(Recepteskonyv.Felhasznalo_id=$id) where Recept.nev like '%{$kereses}%' and Felhasznalo.felhasznalo_id=$id ") or die(mysqli_error($link));
              
              if(mysqli_num_rows($cel)>0) echo "<tr><th>".'Recept neve '."</th><th>".'Elkészítési idő'."</th><th>".'Nehézségi szint'."</th><th>".'Megjegyzések'."</th><th>".'Módosítás'."</th></tr>";
              else die('<span class="btn btn-<?php echo $tema?>" style="font-size:20px;font-weight: bold;font-style: italic; background-color: grey; color:white">Nincs a keresésnek megfelelő találat.</span>');

              while($row=mysqli_fetch_assoc($cel))
              {
                $recept_id=$row['recept_id'];
              
                echo'<tr>                  
                    <td><a class="table-item table-link" href="sikeres_recept_iras.php?tema='.$tema.'&recept_id='.$recept_id.'">'.$row["nev"].'</a></td>
                    <td>'.$row["time_min"].' perc</td>
                    <td>'.$row["nehezsegi_szint"].'</td>
                    <td>'.$row["megjegyzest"].'</td>
                    <td><a class="table-item table-link" href="veglegesites.php?tema='.$tema.'&recept_id='.$row['recept_id'].'">Törlés a kedvencek közül</a></td>                  
                </tr>';
              }               
        
              mysqli_free_result($cel);
              mysqli_close($link);                       
                  
            }
            else if(isset($_POST['cel']) and $_POST['valasztas']==1) //ha a felhasználó a hozzávalókat szeretné kilistázni, ehhez új oldalra lesz irányítva
            {
              header("Location: elfogyott.php?tema=$tema");  
            }        
          } 
          ?>
        </table>
      </h4>
    </div>
  </body>
</html>