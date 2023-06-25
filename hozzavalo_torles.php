<!DOCTYPE html>
<html>
    <div class='text-center'>
        <?php
        session_start();
        include 'db.php';
        include 'menu.php';
        $id=$_SESSION['id'];
        if($id!==0)
        {
            $link=getDB();
            $statusz=$link->query("SELECT statusz from Felhasznalo where felhasznalo_id='{$id}'")->fetch_object()->statusz;
            mysqli_close($link);
        }
        if($statusz!=0)
        {
            $link=getDb();
            if(isset($_GET['hozzavalo_id']))
            {
                $hozzavalo_id=mysqli_real_escape_string($link,$_GET['hozzavalo_id']);
                $_SESSION['hozzavalo_id']=$hozzavalo_id;
            }
            else if(isset($_SESSION['hozzavalo_id']))
                $hozzavalo_id=mysqli_real_escape_string($link,$_SESSION['hozzavalo_id']);
            else 
            {
                mysqli_close($link);    
                die ('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey; color:white">Nincs megadva recept_id</span>'); 
            }
            $link->query("DELETE from Hozzavalok where hozzavalo_id=$hozzavalo_id");
            mysqli_close($link);
            header("Location:receptek.php?tema=$tema");
        }
        else 
        {
            echo'<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey; color:white;">Nincs lehetősége ilyen művelet elvégzésére</span>'; 
            echo'<br/><br/><form action="receptek.php?tema='.$tema.'" method="post">
                    <input type="submit" name="modositas" class="btn btn-'.$tema.'" style="color:white; background-color:mediumorchid" value="Vissza" ></br></br>
                </form>';
        }
        ?>
    </div>
</html>