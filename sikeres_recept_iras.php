<!DOCTYPE html>
<html>
    <?php
    session_start();
    $_SESSION['oldal']='Rögzített recept';
    $id=$_SESSION['id'];
    include 'menu.php';
    include 'db.php';
    $link=getDb();
    if(isset($_GET['recept_id']))
    {
        $recept_id=mysqli_real_escape_string($link,$_GET['recept_id']);
        $_SESSION['recept_id']=$recept_id;
    }
    else if(isset($_SESSION['recept_id']))
        $recept_id=mysqli_real_escape_string($link,$_SESSION['recept_id']);
    else 
    {
        mysqli_close($link);    
        die ('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood; margin: 0 auto">Nincs megadva recept_id</span>'); 
    }
    if($id!==0)
    {
        $statusz=$link->query("SELECT statusz from Felhasznalo where felhasznalo_id='{$id}'")->fetch_object()->statusz;
    }
    ?>
    <head>
        <title>Recept</title>
    </head>
    <body>
        <div class="text-center"> 
            <h4 style="width: 50%; margin: 0 auto;">
                <table class="table table-striped table-<?php echo $tema?> text-center " >
                <br/>
                <?php
                
                $nev=$link->query("SELECT nev From Recept Where recept_id='{$recept_id}'")->fetch_object()->nev;
                echo '<span style="font-size:40px; font-style: italic;">'.$nev.'</span>';
                echo '<span style="font-size:40px; font-style: italic;"> recept leírása</span>';
                
                $query=$link->query("SELECT Hozzavalo_id, mennyiseg from Osszetevok_listaja where Recept_id='{$recept_id}'");
                
                if (!$query or mysqli_num_rows($query)==0) 
                {
                    mysqli_close($link); 
                    echo '<br/><br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Hiba történt ugyanis ilyen recept nem létezik, vagy rosszul lett feltöltve, mert egyetlen hozzávalót se csatoltak hozzá.</span>';
                    echo '<form action="otthon.php?tema='.$tema.'" method="post">
                        <br/><br/><input type="submit" name="vissza" class="btn btn-'.$tema.'" value="Vissza" >
                    </form>';
                    if($statusz!=0)
                    {
                        echo'<form action="modositas.php?tema='.$tema.'" method="post">
                            &nbsp;<input type="submit" name="modosit" class="btn btn-'.$tema.'" value="Módosít" >
                        </form>';
                    }
                }
                else
                {
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
                            <?php 
                                if($row["mennyiseg"]==0) $hiba='style="background-color:red"';
                                else $hiba=Null;
                            ?>
                            <td <?=$hiba?> ><?=$row["mennyiseg"]?></td>
                        </tr>
                
                    <?php endwhile; 
                        $query=$link->query("SELECT megjegyzest from Recept where recept_id=$recept_id"); //Figyelem $query új értéket kapott!!!
                        $eredmeny=mysqli_fetch_assoc($query);
                        echo '<span class="btn btn-<?php echo $tema?>" style= color: slateblue;margin: 0 auto;">'.$eredmeny['megjegyzest'].'</span>';
                        if($hiba) 
                        {    
                            echo('<br/><br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Hibásan töltötte ki az egyik hozzávalóhoz tartozó mennyiség mezőt. <p> Kérem nézze meg, hogy mi a hiba és ez alapján módosítsa a receptet!</p></span></br></br>');             
                        }
                    ?>
                </table>
                    <?php
                    if($statusz!=0)
                    {
                        echo'<form action="modositas.php?tema='.$tema.'" method="post">
                                <input type="submit" name="modositas" class="btn btn-'.$tema.'" style="color:white; background-color:mediumorchid" value="Módosítás" ></br></br>
                            </form>'; 
                        echo'<form action="mennyiseg_modositas.php?tema='.$tema.'" method="post">
                                &nbsp;<input type="submit" name="modosit" class="btn btn-'.$tema.'" value="Érték módosítás" >
                            </form>';
                    }
                }
                    ?>
            </h4>
        </div>
    </body>
</html>
            
