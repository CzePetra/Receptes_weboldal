<!DOCTYPE html>
<html>
  <?php
  session_start();
  $_SESSION['oldal']='Receptek';
  $_SESSION['atiranyitott_oldal']='receptek'; 
  include 'menu.php';
  if(!isset($_SESSION['bejelentkezve']))
  {
    header("Location: bejelentkezes.php?tema=$tema");    
  }
  else
  {
    if($_SESSION['bejelentkezve']==0)
    header("Location: bejelentkezes.php?tema=$tema");
  };
  ?>
  <head>
    <title>Receptek</title>
  </head>

  <body class="<?php echo $tema?>">
    <div class="text-center">
      <nav class="navbar navbar-expand-lg navbar-<?php echo $tema?> bg-<?php echo $tema?>">
        <a class="navbar-brand" href="#">Keressen egyszerűen! <br></a>
          <div class="navbar-nav">
            <form class="form-inline my-2 my-lg-0" method="post">
              
              <label>Hozzávalók </label> 
              &nbsp; <!-- szebb kinézet miatt, hogy ne csússzanak -->
              <input class="form-check form-check-inline" type="radio" name="valtas" value="1"  >
              
              <label class="form-check-label" for="recept">Receptek</label> 
              &nbsp; <!-- szebb kinézet miatt, hogy ne csússzanak egybe-->
              <input class="form-check form-check-inline" type="radio" name="valtas" value="2"  >

              <input class="form-control mr-sm-2"  name='cel' type="text" placeholder="Keresés" aria-label="Keresés">
              <button class="btn btn-<?= $tema?>" type="submit">Keresés</button>
            </form>
            <a class="nav-item nav-link" href="receptek_hozzavalo_felvetele.php?tema=<?php echo $tema?>">Recept és hozzávaló felvétel</a>
            <a class="nav-item nav-link" href="kijelentkezes.php?tema=<?php echo $tema?>">Kijelentkezés</a>
          </div>
      </nav>
      <br/>
      <h1 class="text-center">Ezen az oldalon egyszerűen kereshet a receptek és a hozzávalók között </h1>
      <h4 style="width: 85%; margin: 0 auto;">
        <br><br>    
        <table class="table table-striped table-<?php echo $tema?> text-center " >
          <?php 

          if(isset($_POST['cel']) and !isset($_POST['valtas']))
            echo'<br/><span class="btn btn-<?php echo $tema?>" style="font-size:20px;font-weight: bold;font-style: italic; background-color: grey">Válasszon a lehetőségek közül!</span>';

          else if(isset($_POST['cel']) and ($_POST['valtas'])==2) //receptek közötti keresés
          {
            include 'db.php';
            $link=getDb();
            $kereses=mysqli_real_escape_string($link,$_POST['cel']);
            $cel=$link->query("SELECT * From Recept where nev like '%{$kereses}%' ") or die(mysqli_error($link));
            if(mysqli_num_rows($cel)!=0) echo "<tr><th>".'Recept neve '."</th><th>".'Elkészítési idő'."</th><th>".'Nehézségi szint'."</th><th>".'Megjegyzések'."</th><th>".'Adatok módosítása'."</th><th>".'Törlés'."</th></tr>"; //táblázat fejléce
            else echo'<br/><span class="btn btn-<?php echo $tema?>" style="font-size:20px;font-weight: bold;font-style: italic; background-color: grey">Nincs a keresésnek megfelelő találat!</span>';

            while($row=mysqli_fetch_assoc($cel)) :
                $recept_id=$row['recept_id'];
            ?>
            <tr>
                <td><a class="table-item table-link" href="sikeres_recept_iras.php?tema=<?php echo$tema?>&recept_id=<?php echo $recept_id?>"><?=$row["nev"]?></a></td>
                <td><?=$row["time_min"]?> perc</td>
                <td><?=$row["nehezsegi_szint"]?></td>
                <td><?=$row["megjegyzest"]?></td>
                <td><a class="table-item table-link" href="recept_modositas.php?tema=<?php echo $tema?>&recept_id=<?=$row['recept_id']?>">Módosítás</a></td>
                <td><a class="table-item table-link" href="recept_torles.php?tema=<?php echo $tema?>&recept_id=<?=$row['recept_id']?>">Törlés</a></td>
            </tr>
            <?php 
            endwhile;  
            
            mysqli_free_result($cel);
            mysqli_close($link);
          }

          else if(isset($_POST['cel']) and ($_POST['valtas'])==1) //hozzávalók közötti keresés
          {
            include 'db.php';
            $link=getDb();
            $kereses=mysqli_real_escape_string($link,$_POST['cel']);
            $cel=$link->query("SELECT * From Hozzavalok where nev like '%{$kereses}%' ") or die(mysqli_error($link));
            if(mysqli_num_rows($cel)!=0) echo "<tr><th>".'Hozzávalók'."</th><th>".'Adatok módosítása'."</th><th>".'Törlés'."</tr>"; //táblázat fejléce
            else echo'<br/><span class="btn btn-<?php echo $tema?>" style="font-size:20px;font-weight: bold;font-style: italic; background-color: grey">Nincs a keresésnek megfelelő találat!</span>';

            while($row=mysqli_fetch_assoc($cel)) :
              ?>
              <tr>
                  <td><?=$row["nev"]?></td>
                  <td><a class="table-item table-link" href="hozzavalo_modositas.php?tema=<?php echo $tema?>&hozzavalo_id=<?=$row['hozzavalo_id']?>">Módosítás</a></td>
                  <td><a class="table-item table-link" href="hozzavalo_torles.php?tema=<?php echo $tema?>&hozzavalo_id=<?=$row['hozzavalo_id']?>">Törlés</a></td>
              
              </tr>

              <?php 
              endwhile;  
              mysqli_free_result($cel);
              mysqli_close($link);
          }
          ?>
        </table>
      </h4>  
    </div>
  </body>
</html>
