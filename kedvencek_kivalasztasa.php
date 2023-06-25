<!DOCTYPE html>
<html>
    <?php 
    session_start();
    $_SESSION['oldal']='Kedvenc receptek kiválasztása';
    $_SESSION['atiranyitott_oldal']='feltoltes';
    $id=$_SESSION['id'];
    include 'menu.php';
    include 'db.php';
    $link=getDb();

    $_SESSION['rogzult_receptek']=0; //a visszalépés miatt kell
    if(!isset($_SESSION['bejelentkezve']))
    {
        header("Location: bejelentkezes.php?tema=$tema");          
    }
    else
    {
        if($_SESSION['bejelentkezve']==0)
        header("Location: bejelentkezes.php?tema=$tema");
    }   
    ?>
    <head>
        <title>Kedvencek választása</title>
    </head>
    <body>
        <div class="text-center">     
            <h4 style="width: 70%; margin: 0 auto;">
            <br/>
                <table class="table table-striped table-<?=$tema?> text-center " >
                    <form action="kedvencek_kivalasztasa.php?tema=<?=$tema?>", method="post">
                    
                    <?php
                    $nem_kedvenc=$link->query("SELECT Recept.recept_id, Recept.nev from Recept left join Recepteskonyv on (Recept.recept_id=Recepteskonyv.Recept_id and Recepteskonyv.Felhasznalo_id=$id)where Recepteskonyv.Recept_id is null");
                    $receptek=$link->query("SELECT recept_id, nev from Recept");
                    $sum=mysqli_num_rows($receptek);
                    $fejlec=0;
                    $checkbox_szamlalo=0;
                        
                    if($sum==0) 
                    {
                        mysqli_close($link);
                        die ('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey;margin: 0 auto">Nincs egyetlen elérhető recept sem az adatbázisban.</span>');
                    }
                    
                    if(mysqli_num_rows($nem_kedvenc)!=0)
                    {
                        while($row=mysqli_fetch_assoc($nem_kedvenc))
                        {
                            if($fejlec==0)
                            {
                                echo "<tr><th>".'Receptek'."</th><th>".'Kedvenc?'."</th></tr>";
                                $fejlec=1;
                            }
                            $checkbox_szamlalo++;
                            $sor=$row['recept_id'];
                            $nev="checkbox".$checkbox_szamlalo;
                            echo'
                            <tr>
                                <td>'.$row["nev"].'</td>
                                <td><input class="form-check-input" type="checkbox" name="'.$nev.'" value="'.$sor.'" ></td>
                            </tr>';   
                        }       
                echo'</table>
                            <input type="submit" name="kesz" class="btn btn-<?=$tema?>" style="color: black; background-color:lightblue" value="Mentés" >
                            &nbsp;&nbsp;
                            <input type="reset" name="torol" class="btn btn-<?=$tema?>" style="color: black; background-color:lightblue" value="Törlés" >
                    </form>';   
                    }
                    else echo('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey;margin: 0 auto">Az elérhető recepteket már a kedvencekhez adta</span>');
                            
                    if(isset($_POST['kesz']))
                    {
                        while($checkbox_szamlalo!=0)
                        {
                            $nev="checkbox".$checkbox_szamlalo;
                            if(isset($_POST[$nev]))
                            {
                                $sor=$_POST[$nev];
                                $link->query("INSERT into Recepteskonyv  (Recept_id, Felhasznalo_id) values ('$sor', '$id') ");
                                $_SESSION['rogzult_receptek']=1;
                            }
                            $checkbox_szamlalo--;
                        }
                        $atiranyitas= "sikeres_frissites.php?tema=$tema";
                                echo "<script>window.location.href = '{$atiranyitas}';</script>";
                                exit();
                    }
                    mysqli_close($link);
                    ?>
        </div>
    </body>
</html>