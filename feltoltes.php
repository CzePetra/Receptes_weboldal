<!DOCTYPE html>
<html>
    <?php 
    session_start();
    $_SESSION['oldal']='Alapanyagok felvétele';
    $_SESSION['atiranyitott_oldal']='feltoltes';
    $id=$_SESSION['id'];
    include 'menu.php';
    include 'db.php';
    
    $_SESSION['rogzult_hozzavalo']=0; //a vissza lépés lehetősége miatt kell
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
        <title>Alapanyag felvétel</title>
    </head>
    <body>
        <div class="text-center">       
            <h4 style="width: 70%; margin: 0 auto;">
            <br/>
                <table class="table table-striped table-<?=$tema?> text-center " >
                    <form action="feltoltes.php?tema=<?=$tema?>", method="post">
                    
                    <?php
                    $link=getDB();
                    $nincs_lista=$link->query("SELECT Hozzavalok.hozzavalo_id, Hozzavalok.nev from Hozzavalok left join Otthon on (Hozzavalok.hozzavalo_id=Otthon.hozzavalo_id and Otthon.felhasznalo_id='{$id}')where Otthon.hozzavalo_id is null");
                    $hozzavalok=$link->query("SELECT hozzavalo_id, nev from Hozzavalok");
                    $sum=mysqli_num_rows($hozzavalok);
                    $fejlec=0;
                    $checkbox_szamlalo=0;
                        
                    if($sum==0) die ('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey;margin: 0 auto">Nincs a hozzávalók adatbázisában egyetlen adat sem.</span>');
                    
                    if(mysqli_num_rows($nincs_lista)!=0)
                    {
                        while($row=mysqli_fetch_assoc($nincs_lista))
                        {
                            if($fejlec==0)
                            {
                                echo "<tr><th>".'Hozzávalók'."</th><th>".'Felvétel'."</th></tr>";
                                $fejlec=1;
                            }
                            $checkbox_szamlalo++;
                            $sor=$row['hozzavalo_id'];
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
                    else echo('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey;margin: 0 auto">Nincs hiányzó hozzávalója!</span>');
                        
                    if(isset($_POST['kesz']))
                    {
                        while($checkbox_szamlalo!=0)
                        {
                            $nev="checkbox".$checkbox_szamlalo;
                            if(isset($_POST[$nev]))
                            {
                                $sor=$_POST[$nev];
                                $link->query("INSERT into Otthon  (hozzavalo_id, felhasznalo_id) values ('$sor', '$id') ");
                                $_SESSION['rogzult_hozzavalo']=1;
                            }
                            $checkbox_szamlalo--;
                        }
                        mysqli_close($link);
                        $atiranyitas= "sikeres_frissites.php?tema=$tema"; 
                        echo "<script>window.location.href = '{$atiranyitas}'</script>";
                        exit();
                        //header("Location: sikeres_frissites.php?tema=$tema"); itt se működik :(
                    }
                    ?>
            </h4>
        </div>
    </body>
</html>