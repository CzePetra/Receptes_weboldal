<!DOCTYPE html>
<html>
    <?php 
    session_start();
    $_SESSION['oldal']='Recept modosítása';
    include 'menu.php';
    include 'db.php';

    if(isset($_GET['recept_id']))
    {
        $link=getDB();
        $modositani_kell_id=mysqli_real_escape_string($link,$_GET['recept_id']);
        $_SESSION['recept_id']=$modositani_kell_id;
        mysqli_close($link);
    }
    else if(isset($_SESSION['recept_id']))
    {
        $link=getDB();
        $modositani_kell_id=mysqli_real_escape_string($link,$_SESSION['recept_id']);
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
            else die('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:white">Hiba történt próbálja újra vagy forduljon a koordinátorhoz</span>');
            ?>
            <div class="navbar-nav">
                <form class="form-inline my-2 my-lg-0" method="post">
                <a class="nav-item nav-link" href="kijelentkezes.php?tema=<?php echo $tema?>">Kijelentkezés</a>
                </form>
            </div>
            </nav>

            <h1 class="text-center">Amennyiben van jogosultsága itt átírhatja a receptet.</h1>
            <h4 style="width: 85%; margin: 0 auto; ">
            <br/>  
            <table class="table table-striped table-<?php echo $tema?> text-center " >
                <?php  
                if($statusz==0) 
                {
                echo'<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey; color:white">Nincs lehetősége ilyen művelet elvégzésére</span>'; 
                echo'<br/><br/><form action="receptek.php?tema='.$tema.'" method="post">
                        <input type="submit" name="modositas" class="btn btn-'.$tema.'" style="color:white; background-color:mediumorchid" value="Vissza" ></br></br>
                    </form>';
                }
                else
                {
                    $link=getDB();     
                    $minta=$link->query("SELECT * from Recept where recept_id=$modositani_kell_id");
                    $row=mysqli_fetch_assoc($minta);
                 
                    echo'<form action="recept_modositas.php?tema='.$tema.'" method="post">
                    <input type="text" name="nev" class="btn btn-light" style="width: 500px" placeholder="'.$row['nev'].'">
                        <br/> <br/>
                    <input type="number" name="time" class="btn btn-light" style="width: 500px" placeholder="'.$row['time_min'].'">
                        <br/> <br/>
                    <input type="text" name="megjegyzes" class="btn btn-light" style="width: 500px" placeholder="'.$row['megjegyzest'].'">
                        <br/> <br/>
                    <select name="nehezsegi_szint" class="btn btn-light"  style="width: 250px">
                        <option value="">'.$row['nehezsegi_szint'].'</option>
                        <option value="1">Extra kezdő</option>
                        <option value="2">Kezdő</option>
                        <option value="3">Közepes</option>
                        <option value="4">Haladóknak</option>
                        <option value="5">Átgondolandó</option>
                    </select>
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
                            $link->query("UPDATE Recept set nev='$nev'  where recept_id=$modositani_kell_id");  
                            echo ('<br/><br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue">Módosítva lett a recept neve!</span>');
                        }
                        if(!empty($_POST['time'])) 
                        {
                            $time_min=mysqli_real_escape_string($link,$_POST['time']); 
                            $link->query("UPDATE Recept set time_min='$time_min'  where recept_id=$modositani_kell_id");  
                            echo ('<br/><br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue">Módosítva lett a recept idő!</span>');
                        }
                        if(!empty($_POST['megjegyzes']))
                        {
                            $megjegyzest=mysqli_real_escape_string($link,$_POST['megjegyzes']); 
                            $link->query("UPDATE Recept set megjegyzest='$megjegyzest'  where recept_id=$modositani_kell_id");  
                            echo ('<br/><br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue">Módosítva lett a recepthez fűzött megjegyzés!</span>');
                        }
                        if(!empty($_POST['nehezsegi_szint'])) 
                        {
                            $nehezsegi_szint=mysqli_real_escape_string($link,$_POST['nehezsegi_szint']); 
                            $link->query("UPDATE Recept set nehezsegi_szint='$nehezsegi_szint'  where recept_id=$modositani_kell_id");  
                            echo ('<br/><br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue">Módosítva lett a recept nehézségi szintje!</span>');
                        }
                        if(empty($_POST['nev']) and empty($_POST['time']) and empty($_POST['megjegyzes']) and empty($_POST['nehezsegi_szint'])) 
                            echo ('<br/><br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey; color:white">Nem történt módosítás</span>');
                        else echo('<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey">Ahhoz, hogy ezen az oldalon is lássa a frissítéseket a mezőkbe írva, frissítse az oldalt</span>');
                        mysqli_free_result($minta);
                        mysqli_close($link);
                    }
                    else if(isset($_POST['megse']))
                    {
                        mysqli_free_result($minta);
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