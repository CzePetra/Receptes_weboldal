<!DOCTYPE html>
<html>
  <?php 
    session_start();
    $_SESSION['oldal']='Elfogyott alapanyagok rögzítése';
    $_SESSION['atiranyitott_oldal']='elfogyott';
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
    <title>Alapanyagok</title>
  </head>
  <body>
    <div class="text-center">  
      <h4 style="width: 70%; margin:0 auto">
      <br/>
      <table class="table table-striped table-<?=$tema?> text-center " >
        <form action="elfogyott.php?tema=<?=$tema?>", method="post">
        <?php 
          $link=getDB();

          $van_lista=$link->query("SELECT Hozzavalok.nev as nev, Hozzavalok.hozzavalo_id as id, Felhasznalo.felhasznalo_id as felhasznalo From Otthon inner join Hozzavalok on (Otthon.hozzavalo_id=Hozzavalok.hozzavalo_id) inner join Felhasznalo on (Otthon.felhasznalo_id=Felhasznalo.felhasznalo_id) where Felhasznalo.felhasznalo_id=$id order by Hozzavalok.hozzavalo_id");

          if(mysqli_num_rows($van_lista)>0) echo "<tr><th>".'Hozzávalók'."</th><th>".'Elfogyott'."</th></tr>";
          else die('<span class="btn btn-<?php echo $tema?>" style="font-size:20px;font-weight: bold;font-style: italic; background-color: grey; color:white">Az éléskamra üres!</span>');
          $szamlalo=0;

          while($row=mysqli_fetch_assoc($van_lista))
          {
            $szamlalo++;
            $sor=$row['id'];
            $nev="checkbox".$szamlalo;
            echo '<tr>
              <td>'.$row["nev"].'</td>
              <td><input class="form-check-input" type="checkbox" name="'.$nev.'" value="'.$sor.'" ></td>
            </tr>';           
          }
        ?>

      </table>
        <input type="submit" name="kesz" class="btn btn-<?=$tema?>" style="color: black; background-color:lightblue" value="Mentés" >
        &nbsp;&nbsp;
        <input type="reset" name="torol" class="btn btn-<?=$tema?>" style="color: black; background-color:lightblue" value="Törlés" >
        </form>
        <br/><br/>
        <form action="ajanlas.php?tema=<?php echo $tema ?>" method="post">
          <input type="submit" class="btn btn-<?=$tema?>" style="color:black; background-color:lightblue" value="Recept ajánlás" >
        </form>
        <?php
      
        if(isset($_POST['kesz']))
        {
          while($szamlalo!=0)
          {
            $nev="checkbox".$szamlalo;
            
            if(isset($_POST[$nev]))
            {
              $sor=$_POST[$nev];
              $link->query("DELETE from Otthon where hozzavalo_id=$sor and felhasznalo_id=$id");
            }
            $szamlalo--;
          }
          mysqli_close($link); 
          $atiranyitas= "otthon.php?tema=$tema"; 
          echo "<script>window.location.href = '{$atiranyitas}'</script>";
          exit();
        }   

      ?>
      </h4>
    </div>
  </body>
</html>