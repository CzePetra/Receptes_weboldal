<!DOCTYPE html>
<html>
  <?php 
  session_start();
  $_SESSION['oldal']='Felhasználói adatok modosítása';
  $_SESSION['atiranyitott_oldal']='felhasznalo_modositas.php';
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
    $statusz=$link->query("SELECT statusz from Felhasznalo where felhasznalo_id=$id")->fetch_object()->statusz;
    mysqli_close($link);
  }
  else die('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Hiba történt próbálja újra vagy forduljon a koordinátorhoz</span>');

  if($statusz!=2) die('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Hiba történhetett ezen az oldalon Ön nem is lehetne!</span>');
  
  if(isset($_GET['felhasznalo_id']))
  {
    $link=getDB();
    $modositani_kell_id=mysqli_real_escape_string($link,$_GET['felhasznalo_id']);
    $_SESSION['felhasznalo_id']=$modositani_kell_id;
    mysqli_close($link);
  }
  else if(isset($_SESSION['felhasznalo_id']))
  {
    $link=getDB();
    $modositani_kell_id=mysqli_real_escape_string($link,$_SESSION['felhasznalo_id']);
    mysqli_close($link);
  }
  else die ('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">"Nincs megadva id"</span>'); 
  ?>
  <head>
    <title>Felhasználói adatok modosítása</title>
  </head>
  <body>
    <div class="<?php echo $tema?>">
      <div class="text-center"> 
        <nav class="navbar navbar-expand-lg navbar-<?php echo $tema?> bg-<?php echo $tema?>">
          
          <div class="navbar-nav">
            <form class="form-inline my-2 my-lg-0" method="post">
              <a class="nav-item nav-link" href="kijelentkezes.php?tema=<?php echo $tema?>">Kijelentkezés</a>
            </form>
          </div>
        </nav>

        <h1 class="text-center">Írja át a módosítani kívánt mezőket</h1>
        <h4 style="width: 85%; margin: 0 auto; ">
        <br/>  
          <table class="table table-striped table-<?php echo $tema?> text-center " >
            <?php  
            $link=getDB();     
            $minta=$link->query("SELECT * from Felhasznalo where felhasznalo_id=$modositani_kell_id");
            if($row=mysqli_fetch_assoc($minta));
            {
              $modositott=0;
              $statusz=$row['statusz'];
              if($statusz==0) $jogkor='Alap felhasználó';
              else if($statusz==1) $jogkor='Séf felhasználó';
              else if($statusz==2) $jogkor='Koordinátor felhasználó';
              
              echo'<form action="felhasznalo_modositas.php?tema='.$tema.'" method="post">
              <input type="text" name="nev" class="btn btn-light" style="width: 500px" placeholder="'.$row['nev'].'">
                <br/> <br/>
              <input type="int" name="email" class="btn btn-light" style="width: 500px" placeholder="'.$row['email'].'">
                <br/> <br/>
              <input type="text" name="jelszo" class="btn btn-light" style="width: 500px" placeholder="Új jelszó megadása">
                <br/> <br/>
              <input type="text" onfocus="(this.type="date")" name="sz_ido" class="btn btn-light"  style="width: 500px" placeholder="'.$row['szuletesnap'].'">
                <br/> <br/>
              <select name="statusz" class="btn btn-light"  style="width: 350px">
                <option value="'.$statusz.'">'.$jogkor.'</option>
                <option value="0">Alap</option>
                <option value="1">Séf (recept szerkeztő)</option>
                <option value="2">Koordinátor</option>
              </select>
              <input type="submit" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Rögzités" name="ment"> 
              <input type="reset" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Törlés"> 
              </form>';
              if(isset($_POST['ment']))
              {
                if(!empty($_POST['nev']))
                {
                  $nev=mysqli_real_escape_string($link,$_POST['nev']); 
                  $link->query("UPDATE Felhasznalo set nev='$nev'  where felhasznalo_id=$modositani_kell_id");  
                  $modositott++;
                }
                if(!empty($_POST['email']))
                {
                  $email=mysqli_real_escape_string($link,$_POST['email']); 
                  $link->query("UPDATE Felhasznalo set email='$email' where felhasznalo_id=$modositani_kell_id");  
                  $modositott++;
                }
                if(!empty($_POST['jelszo']))
                {
                  $jelszo=mysqli_real_escape_string($link,$_POST['jelszo']);
                  $titkositott_jelszo=password_hash($jelszo, PASSWORD_DEFAULT); 
                  $link->query("UPDATE Felhasznalo set jelszo='$titkositott_jelszo'  where felhasznalo_id=$modositani_kell_id");  
                  $modositott++;
                }
                if(!empty($_POST['sz_ido']))
                {
                  $datum=mysqli_real_escape_string($link,$_POST['sz_ido']); 
                  $link->query("UPDATE Felhasznalo set szuletesnap='$datum'  where felhasznalo_id=$modositani_kell_id");  
                  $modositott++;
                }
                if(isset($_POST['statusz']) and $statusz!=$_POST['statusz'])
                {
                  $statusz=mysqli_real_escape_string($link,$_POST['statusz']); 
                  $link->query("UPDATE Felhasznalo set statusz='$statusz'  where felhasznalo_id=$modositani_kell_id");  
                  $modositott++;
                }
                
                if($modositott!=0) echo ('<br/><br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue;margin: 0 auto;">Sikeresen módosította a felhasználó '.$modositott.' adatat!</span>');
                else echo ('<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey;margin: 0 auto">Nem hajtott végre egyetlen módosítást sem</span>');
              }
            }
            mysqli_free_result($minta);
            mysqli_close($link);
            ?>
          </table>
        </h4>
      </div>
    </div>
  </body>
</html>