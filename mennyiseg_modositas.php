<!DOCTYPE html> 
<html>
    <?php
        session_start();
        $_SESSION['oldal']='Érték megadása';
        include 'menu.php';
        include 'db.php';
    ?>
    <head>
        <title>Érték változtatás</title>
    </head>
    <body>
        <div class="text-center"> 
        <?php
        $link=getDb();
        if(isset($_SESSION['recept_id']))
        $recept_id=mysqli_real_escape_string($link,$_SESSION['recept_id']);
        
        $hozzavalok_listaja=$link->query("SELECT Hozzavalok.hozzavalo_id, Hozzavalok.nev, Osszetevok_listaja.mennyiseg from Hozzavalok left join Osszetevok_listaja on (Hozzavalok.hozzavalo_id=Osszetevok_listaja.Hozzavalo_id) and Osszetevok_listaja.Recept_id=$recept_id where Osszetevok_listaja.Hozzavalo_id is not null ");
        $index=0;

        while($row=mysqli_fetch_assoc($hozzavalok_listaja)): 
            $index++;
            $ertek="mennyiseg".$index;
            $mennyiseg_ertek=$row['mennyiseg'];
        ?>
        
        <form action="mennyiseg_modositas.php?tema=<?php echo $tema?>&recept_id=<?php echo $recept_id?>" method="post">
            <br/> <br/>
            <div class="form-check form-check-inline" class="row">
                <label class="btn btn-<?php echo $tema?>" for="<?php echo $index?>" style="color: black; background-color:lightblue"><?=$row['nev']?></label> 
                &nbsp;&nbsp; <!-- két szóköz a könnyebb olvashatóság miatt -->
                <div class="btn btn-light">
                    <input type="number" name="<?php echo $ertek?>"  value="<?php echo $mennyiseg_ertek?>">
                    <br/>
                </div>
            </div>       

            <?php endwhile;
                $recept=$link->query("SELECT * from Recept where recept_id='{$recept_id}'");
                if ($row= mysqli_fetch_assoc($recept))
                    $recept_nev=(string) $row['nev'];
                else die ('<br/><br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Nincs ilyen recept_id</span>');    
            ?>
            <br/> <br/>
            <input type="submit" name="kesz" class="btn btn-<?php echo $tema?>" style="color: black; background-color:lightblue" value="Értékek módosítása a <?php echo $recept_nev?> receptnél" > 
        </form>
        <?php                 
        if(isset($_POST['kesz']))
        {
            $hozzavalok_listaja=$link->query("SELECT Hozzavalok.hozzavalo_id, Hozzavalok.nev, Osszetevok_listaja.mennyiseg from Hozzavalok left join Osszetevok_listaja on (Hozzavalok.hozzavalo_id=Osszetevok_listaja.Hozzavalo_id) and Osszetevok_listaja.Recept_id=$recept_id where Osszetevok_listaja.Hozzavalo_id is not null ");
            $legalabb_egy=0;
            $szamlalo=1;
            $index++;
            while($szamlalo<$index)
            {
                $row=mysqli_fetch_assoc($hozzavalok_listaja);
                $hozzavalo_id=$row['hozzavalo_id'];
                $mennyiseg="mennyiseg".$szamlalo;
                $checkbox_szam='checkbox'.$szamlalo;
                
                if($_POST[$mennyiseg]==0)
                {
                    $link->query("DELETE from Osszetevok_listaja where Recept_id=$recept_id and Hozzavalo_id=$hozzavalo_id");    
                }
                $mennyiseg_ertek=$_POST[$mennyiseg];
                $insert_query = ("UPDATE Osszetevok_listaja set  mennyiseg='$mennyiseg_ertek' where recept_id=$recept_id and hozzavalo_id=$hozzavalo_id");
                mysqli_query($link, $insert_query);

                $szamlalo++;
            }
            mysqli_close($link);
            
            $atiranyitas= "sikeres_recept_iras.php?tema=$tema";
            echo "<script>window.location.href = '{$atiranyitas}';</script>";
            exit();
            
        }
        ?>    
        </div>
    </body>
</html>    
