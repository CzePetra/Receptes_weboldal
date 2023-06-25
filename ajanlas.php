<!DOCTYPE html>
<html>
  <?php 
    session_start();
    $_SESSION['oldal']='Ajánlás';
    $_SESSION['atiranyitott_oldal']='ajanlas';
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
    else die('<span class="btn btn-<?php echo $tema?>" style="font-size:20px;font-weight: bold;font-style: italic; background-color: grey; color:white;margin: 0 auto">Hiba történt, jelentkezzen be újra!</span>');
  ?>
  <head>
    <title>Ajánlott receptek</title>
  </head>
  <body>
    <div class="text-center">  
      <h4 style="width: 85%; margin: 0 auto;">
      <br/>
      <table class="table table-striped table-<?=$tema?> text-center " >
        <?php 
        $link=getDB();
        $fejléc=0;
        $receptek=$link->query("SELECT Recept.recept_id, Recept.nev, Recept.time_min, Recept.nehezsegi_szint, Recept.megjegyzest from Recept");
        while($row=mysqli_fetch_assoc($receptek))
        {
            $recept_id=$row['recept_id'];
            $kivalasztott=$link->query("SELECT Osszetevok_listaja.Hozzavalo_id as szukseges_id from Osszetevok_listaja left join Otthon on(Otthon.hozzavalo_id=Osszetevok_listaja.Hozzavalo_id and Otthon.felhasznalo_id=$id) where Otthon.hozzavalo_id is null and Osszetevok_listaja.Recept_id=$recept_id");
            if(mysqli_num_rows($kivalasztott)==0)
            {
                if($fejléc==0)
                {
                    echo "<tr><th>".'Recept'."</th><th>".'Elkészítési idő'."</th><th>".'Nehézségi szint'."</th><th>".'Megjegyzések'."</th></tr>";
                    $fejléc++;
                }
                $nev=$row["nev"];                
                echo'<tr>
                        <td><a class="table-item table-link" href="sikeres_recept_iras.php?tema='.$tema.'&recept_id='.$recept_id.'">'.$nev.'</a></td>
                        <td>'.$row["time_min"].' perc</td>
                        <td>'.$row["nehezsegi_szint"].'</td>
                        <td>'.$row["megjegyzest"].'</td>
                    </tr>';                
            }
        }
        if($fejléc==0) 
        {
            echo('<span class="btn btn-<?php echo $tema?>" style="font-size:20px;font-weight: bold;font-style: italic; background-color: grey; color:white">Nincs elég alapanyag egyetlen recept elkészítéséhez sem!</span>');
        }     
        mysqli_free_result($kivalasztott);
        mysqli_free_result($receptek);
        mysqli_close($link);
        ?>

        </table>
        <form action="elfogyott.php?tema=<?php echo $tema?>">
        <input type="submit" class="btn btn-'.$tema.'" style="color:white; background-color:mediumorchid" value="Vissza" ></br></br>
        </form>
      </h4>
    </div>
  </body>
</html>