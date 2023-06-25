<!DOCTYPE html>
<html>
  <?php 
  session_start();
  $_SESSION['oldal']='Felhasználói jogkör';
  $_SESSION['atiranyitott_oldal']="statusz";
  include 'menu.php';
  include 'db.php';
  ?>
  <head>
    <title>Státusz lekérdezés/beállítás</title>
    <form action="statusz.php?tema=<?php echo $tema?>" method="post"></form>
  </head>
  <body>
    <div class="<?php echo $tema?>">
      <div class="text-center">
        <nav class="navbar navbar-expand-lg navbar-<?php echo $tema?> bg-<?php echo $tema?>">
          <?php
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
            $statusz=$link->query("SELECT statusz from Felhasznalo where felhasznalo_id='{$id}'")->fetch_object()->statusz;
            mysqli_close($link);
          }
          else die('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Hiba történt próbálja újra!</span>');

          if($statusz==0) $jogkor='Alap felhasználó';
          else if($statusz==1) $jogkor='Séf felhasználó';
          else if($statusz==2) $jogkor='Koordinátor felhasználó';                  
          ?>

          <a class="navbar-brand" href="#">Felhasználók lekérdezése</a>
            <div class="navbar-nav">
              <form class="form-inline my-2 my-lg-0" method="post">
                <input class="form-control mr-sm-2"  name='cel' type="text" placeholder="Keresés" aria-label="Keresés">
                <button class="btn btn-<?= $tema?>" type="submit">Keresés</button>
                <a class="nav-item nav-link" href="kijelentkezes.php?tema=<?php echo $tema?>">Kijelentkezés</a>              
                <a class="nav-item"><?php echo $jogkor?></a>
              </form>
            </div>
        </nav>
        <h1 class="text-center">Felhasználók és jogkörök kezelése</h1>
        <h4 style="width: 85%; margin: 0 auto; ">
          <br/>
          A kereséssel szűrheti a felhasználókat   
          <table class="table table-striped table-<?php echo $tema?> text-center " >
            <?php 
            if(isset($_POST['cel']) and $jogkor=='Koordinátor felhasználó')
            {
              $link=getDB();
              $kereses=mysqli_real_escape_string($link,$_POST['cel']);
              $cel=$link->query("SELECT * From Felhasznalo where nev like '%{$kereses}%' ") or die(mysqli_error($link));

              echo "<tr><th>".'Felhasználó id '."</th><th>".'Név '."</th><th>".'Email cím'."</th><th>".'Születésnap'."</th><th>".'Jogkör'."</th><th>".'Adatok módosítása'."</th><th>".'Törlés'."</th></tr>"; //táblázat fejléce

              while($row=mysqli_fetch_assoc($cel))
              {
                if($row["felhasznalo_id"]==1 or $row["felhasznalo_id"]==$id) 
                {
                  echo '<tr>
                  <td>'.$row["felhasznalo_id"].'</td>
                  <td>'.$row["nev"].'</td>
                  <td>'.$row["email"].'</td>
                  <td>'.$row["szuletesnap"].'</td>
                  <td>'.$row["statusz"].'</td>
                  <td>Nem módosítható</td>
                  <td>Nem törölhető</td>
                  </tr>';
                  $row=mysqli_fetch_assoc($cel);
                }

                if($row)
                echo '<tr>
                    <td>'.$row["felhasznalo_id"].'</td>
                    <td>'.$row["nev"].'</td>
                    <td>'.$row["email"].'</td>
                    <td>'.$row["szuletesnap"].'</td>
                    <td>'.$row["statusz"].'</td>
                    <td><a class="table-item table-link" href="felhasznalo_modositas.php?tema='.$tema.'&felhasznalo_id='.$row['felhasznalo_id'].'">Módosítás</a></td>
                    <td><a class="table-item table-link" href="felhasznalo_torles.php?tema='.$tema.'&felhasznalo_id='.$row['felhasznalo_id'].'">Törlés</a></td>
                    </tr>';
              }
              mysqli_free_result($cel);
              mysqli_close($link);
            }
            else if(isset($_POST['cel']))
            {
              $link=getDB();
              $kereses=mysqli_real_escape_string($link,$_POST['cel']);
              $cel=$link->query("SELECT * From Felhasznalo where nev like '%{$kereses}%' ") or die(mysqli_error($link));

              echo "<tr><th>".'Felhasználó neve'."</th><th>".'email címe'."</th><th>".'születésnap'."</th><th>".'státusza'."</th></tr>"; 

              while($row=mysqli_fetch_assoc($cel))
              {
                echo '<tr>
                  <td>'.$row["nev"].'</td>
                  <td>'.$row["email"].'</td>
                  <td>'.$row["szuletesnap"].'</td>
                  <td>'.$row["statusz"].'</td>
                  </tr>';
              }  
              mysqli_free_result($cel);
              mysqli_close($link);
            }
            ?>
          </table>
        </h4>
      </div>
    </div>
  </body>
</html>