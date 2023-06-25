<!DOCTYPE html>
<html>
  <?php 
  session_start();
  $_SESSION['oldal']='Hozzávaló modosítása';
  include 'menu.php';
  include 'db.php';

  if(isset($_GET['hozzavalo_id']))
  {
    $link=getDB();
    $modositani_kell_id=mysqli_real_escape_string($link,$_GET['hozzavalo_id']);
    $_SESSION['hozzavalo_id']=$modositani_kell_id;
    mysqli_close($link);
  }
  else if(isset($_SESSION['hozzavalo_id']))
  {
    $link=getDB();
    $modositani_kell_id=mysqli_real_escape_string($link,$_SESSION['hozzavalo_id']);
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
          <?php

          if(!isset($_SESSION['bejelentkezve']))
          {
            header("Location: bejelentkezes.php?tema=$tema"); 
            $_SESSION['atiranyitott_oldal']='statusz'; 
          }
          else
          {
            if($_SESSION['bejelentkezve']==0)
            header("Location: bejelentkezes.php?tema=$tema");
            $_SESSION['atiranyitott_oldal']='felhasznalo_modositas';
          }
          $id=$_SESSION['id'];
          if($id!==0)
          {
            $link=getDB();
            $statusz=$link->query("SELECT statusz from Felhasznalo where felhasznalo_id=$id")->fetch_object()->statusz;
            mysqli_close($link);
          }
          else die('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Hiba történt próbálja újra vagy forduljon a koordinátorhoz</span>');
          ?>
          <div class="navbar-nav">
            <form class="form-inline my-2 my-lg-0" method="post">
              <a class="nav-item nav-link" href="kijelentkezes.php?tema=<?php echo $tema?>">Kijelentkezés</a>
            </form>
          </div>
        </nav>

        <h1 class="text-center">Amennyiben van jogosultsága itt írhatja át a hozzávaló nevét.</h1>
        <h4 style="width: 85%; margin: 0 auto; ">
        <br/>  
          <table class="table table-striped table-<?php echo $tema?> text-center " >
            <?php  
            if($statusz==0) 
            {
              echo'<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey; color:white; margin: 0 auto">Nincs lehetősége ilyen művelet elvégzésére</span>'; 
              echo'<br/><br/><form action="receptek.php?tema='.$tema.'" method="post">
                      <input type="submit" name="modositas" class="btn btn-'.$tema.'" style="color:white; background-color:mediumorchid" value="Vissza" ></br></br>
                  </form>';
            }
            else
            {
                $link=getDB();     
                $minta=$link->query("SELECT * from Hozzavalok where hozzavalo_id=$modositani_kell_id");
                $row=mysqli_fetch_assoc($minta);
                echo'<form action="hozzavalo_modositas.php?tema='.$tema.'" method="post">
                <input type="text" name="nev" class="btn btn-light" style="width: 500px" placeholder="'.$row['nev'].'">
                    <br/> <br/>
                <input type="submit" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Rögzités" name="ment"> 
                    &nbsp;
                <input type="reset" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Törlés"> 
                    &nbsp;
                <input type="submit" name="megse" class="btn btn-'.$tema.'" style="color:white; background-color:grey" value="Mégse" >
                </form>';
                if(isset($_POST['ment']))
                {
                    if(!empty($_POST['nev']))
                    {
                    $nev=mysqli_real_escape_string($link,$_POST['nev']); 
                    $link->query("UPDATE Hozzavalok set nev='$nev'  where hozzavalo_id=$modositani_kell_id");  
                    echo ('<br/><br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue;margin: 0 auto;">Sikeresen módosította a hozzávaló nevét!</span>');
                    }
                    else echo ('<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey;margin: 0 auto">Még nem módosított</span>');
                    mysqli_free_result($minta);
                    mysqli_close($link);
                }
                else if(isset($_POST['megse']))
                {
                    mysqli_close($link);
                    $atiranyitas= "receptek.php?tema=$tema"; 
                        echo "<script>window.location.href = '{$atiranyitas}'</script>";
                        exit();
                }                
            }
                  
            
            ?>
          </table>
        </h4>
      </div>
    </div>
  </body>
</html>