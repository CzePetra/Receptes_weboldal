<!DOCTYPE html>
<html>
  <?php 
  session_start();
  $_SESSION['oldal']='Felhasználó törlése';
  $_SESSION['atiranyitott_oldal']='felhasznalo_torles.php';
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
    $torolni_kell_id=mysqli_real_escape_string($link,$_GET['felhasznalo_id']);
    $_SESSION['felhasznalo_id']=$torolni_kell_id;
    mysqli_close($link);
  }
  else if(isset($_SESSION['felhasznalo_id']))
  {
    $link=getDB();
    $torolni_kell_id=mysqli_real_escape_string($link,$_SESSION['felhasznalo_id']);
    mysqli_close($link);
  }
  else die ('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">"Nincs megadva id"</span>'); 
  ?>
  <head>
    <title>Felhasználó törlés</title>
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

        <h1 class="text-center">Ellenőrizze, hogy biztosan törölni kívánja-e a kijelölt felhasználót!</h1>
        <h4 style="width: 85%; margin: 0 auto; ">
        <br/>  
          <table class="table table-striped table-<?php echo $tema?> text-center " >
            <?php  
            $link=getDB();     
            $minta=$link->query("SELECT * from Felhasznalo where felhasznalo_id=$torolni_kell_id");
            if($row=mysqli_fetch_assoc($minta));
            {

              $statusz=$row['statusz'];
              if($statusz==0) $jogkor='Alap felhasználó';
              else if($statusz==1) $jogkor='Séf felhasználó';
              else if($statusz==2) $jogkor='Koordinátor felhasználó';
              
              echo'<form action="felhasznalo_torles.php?tema='.$tema.'" method="post">
              <input  type="text" name="nev" readonly class="btn btn-light" style="width: 500px" placeholder="'.$row['nev'].'">
                <br/> <br/>
              <input type="int" name="email" readonly class="btn btn-light" style="width: 500px" placeholder="'.$row['email'].'">
                <br/> <br/>
              <input type="text" onfocus="(this.type="date")" name="sz_ido" readonly class="btn btn-light"  style="width: 500px" placeholder="'.$row['szuletesnap'].'">
                <br/> <br/>
              <select name="statusz" class="btn btn-light"  style="width: 350px">
                <option value="">'.$jogkor.'</option>
              </select>
              <input type="submit" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Végleges törlés" name="ment"> 
              <input type="submit" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Mégse" name=megse> 
              </form>';
              if(isset($_POST['ment']))
              {
                $link->query("DELETE from Felhasznalo where felhasznalo_id=$torolni_kell_id");
                mysqli_free_result($minta);
                mysqli_close($link); 
                $atiranyitas= "statusz.php?tema=$tema"; 
                echo "<script>window.location.href = '{$atiranyitas}'</script>";
                exit();
              }
              if(isset($_POST['megse'])) 
              {
                mysqli_free_result($minta);
                mysqli_close($link); 
                $atiranyitas= "statusz.php?tema=$tema"; 
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