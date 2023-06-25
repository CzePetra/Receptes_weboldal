<!DOCTYPE html>
<html>
    <?php
    session_start();
    $_SESSION['oldal']='Törölni kívánt recept';
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

    
    $link=getDB();
    if(isset($_GET['recept_id']))
    {
        $recept_id=mysqli_real_escape_string($link,$_GET['recept_id']);
        $_SESSION['recept_id']=$recept_id;
    }
    else if(isset($_SESSION['recept_id']))
        $recept_id=mysqli_real_escape_string($link,$_SESSION['recept_id']);
    else 
    {    
        die ('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin-left: 650px">"Nincs megadva recept_id"</span>'); 
        mysqli_close($link);
    }
    ?>
    <head>
        <title>Kedvenc recept törlés</title>
    </head>
    <body>
        <div class="text-center"> 
            <h4 style="width: 50%; margin: 0 auto;">
                <table class="table table-striped table-<?php echo $tema?> text-center " >
                <br/>

                    <?php
                    
                    $nev=$link->query("SELECT nev From Recept Where recept_id=$recept_id")->fetch_object()->nev;
                    echo '<span style="margin: 0 auto; font-size:40px; font-style: italic;">'.$nev.'</span>';
                    
                    $query=$link->query("SELECT Hozzavalo_id, mennyiseg from Osszetevok_listaja where Recept_id='{$recept_id}'");
                    
                    if (!$query) die ('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">"Hiba történt ugyanis ilyen recept nem létezik"</span>');
                    
                    $elso_sor=1;
                    while($row=mysqli_fetch_assoc($query)):        
                        $hozzavalo=$link->query("SELECT nev from Hozzavalok where hozzavalo_id='{$row['Hozzavalo_id']}'")->fetch_object()->nev;
                        if($elso_sor==1)
                        {
                            ?>
                            <br/><br/>
                            <tr>
                                <td>Hozzávalók</td>
                                <td>mennyiség (g/dl/tk/db) mértékekben</td>
                            </tr>
                            <?php $elso_sor=0; 
                        }
                        ?>
                        <tr>
                            <td><?=$hozzavalo?></td>
                            <td><?=$row["mennyiseg"]?></td>
                        </tr>
                
                    <?php endwhile; 
                        $query=$link->query("SELECT megjegyzest from Recept where recept_id='{$recept_id}'"); //Figyelem $query új értéket kapott!!!
                        $eredmeny=mysqli_fetch_assoc($query);
                        echo '<p><span class="btn btn-<?php echo $tema?>" style= color: slateblue;margin: 0 auto;">'.$eredmeny['megjegyzest'].'</span></p>
                </table>';
                    echo('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Ezzel a művelettel véglegesen törli a kedvenc receptjei közül a '.$nev.' receptet.</span></br></br>');
                    echo'<form action="veglegesites.php?tema='.$tema.'" method="post">
                            <input type="submit" name="vegleges_torles" class="btn btn-'.$tema.'" style="color:burlywood; background-color:red" value="Törlés" >
                            <input type="submit" name="megse" class="btn btn-'.$tema.'" style="color:burlywood; background-color:red" value="Mégse" >
                        </form>';

                    if(isset($_POST['vegleges_torles'])) 
                    {
                        $link->query("DELETE from Recepteskonyv where Recept_id=$recept_id and Felhasznalo_id=$id");
                        mysqli_close($link); 
                        $atiranyitas= "otthon.php?tema=$tema"; 
                        echo "<script>window.location.href = '{$atiranyitas}'</script>";
                        exit();
                    }
                    if(isset($_POST['megse'])) 
                    {
                        mysqli_close($link); 
                        $atiranyitas= "otthon.php?tema=$tema"; 
                        echo "<script>window.location.href = '{$atiranyitas}'</script>";
                        exit();
                    }
                    mysqli_close($link); 
                ?>
            </h4>
        </div>
    </body>
</html>