<!DOCTYPE html> 
<html>
    <?php
        session_start();
        $_SESSION['oldal']='Hozzávalók megadása';
        include 'menu.php';
        include 'db.php';
    ?>
    <head>
        <title>Hozzávalók kiválasztása</title>
    </head>
    <body>
        <div class="text-center"> 
        <?php
        $link=getDb();
        if(isset($_SESSION['recept_id']))
        $recept_id=mysqli_real_escape_string($link,$_SESSION['recept_id']);
        
        $hozzavalok_listaja=$link->query("SELECT Hozzavalok.hozzavalo_id, Hozzavalok.nev from Hozzavalok left join Osszetevok_listaja on (Hozzavalok.hozzavalo_id=Osszetevok_listaja.Hozzavalo_id) and Osszetevok_listaja.Recept_id=$recept_id where Osszetevok_listaja.Hozzavalo_id is null ");
        $index=0;

        while($row=mysqli_fetch_assoc($hozzavalok_listaja)): 
            $index++;
            $nev="checkbox".$index;
            $ertek="mennyiseg".$index;
        ?>
        
        <form action="modositas.php?tema=<?php echo $tema?>&recept_id=<?php echo $recept_id?>" method="post">
            <br/> <br/>
            <div class="form-check form-check-inline" class="row">
                <input class="form-check-input" type="checkbox" name="<?php echo $nev?>" value="<?php echo $index?>" >
                <label class="btn btn-<?php echo $tema?>" for="<?php echo $index?>" style="color: black; background-color:lightblue"><?=$row['nev']?></label> 
                &nbsp;&nbsp; <!-- két szóköz a könnyebb olvashatóság miatt -->
                <div class="btn btn-light">
                    <input type="number" name="<?php echo $ertek?>" placeholder="Mennyiség ">
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
            <input type="submit" name="kesz" class="btn btn-<?php echo $tema?>" style="color: black; background-color:lightblue" value="Lista hozzáadása a <?php echo $recept_nev?> recepthez" > 
        </form>
        <?php                 
        if(isset($_POST['kesz']))
        {
            $hozzavalok_listaja=$link->query("SELECT Hozzavalok.hozzavalo_id, Hozzavalok.nev from Hozzavalok left join Osszetevok_listaja on (Hozzavalok.hozzavalo_id=Osszetevok_listaja.Hozzavalo_id) and Osszetevok_listaja.Recept_id=$recept_id where Osszetevok_listaja.Hozzavalo_id is null ");
            $legalabb_egy=0;
            $szamlalo=1;
            $index++;
            while($szamlalo<$index)
            {
                $row=mysqli_fetch_assoc($hozzavalok_listaja);
                $hozzavalo_id=$row['hozzavalo_id'];
                $mennyiseg="mennyiseg".$szamlalo;
                $checkbox_szam='checkbox'.$szamlalo;
                if(isset ($_POST[$checkbox_szam]))
                {
                    $insert_query = "INSERT INTO Osszetevok_listaja (Hozzavalo_id, Recept_id, mennyiseg) VALUES ('$hozzavalo_id','$recept_id','$_POST[$mennyiseg]')";
                    mysqli_query($link, $insert_query);
                    $legalabb_egy=1;
                }
                $szamlalo++;
            }
            mysqli_close($link);
            if($legalabb_egy==1)
            {
                $atiranyitas= "sikeres_recept_iras.php?tema=$tema";
                echo "<script>window.location.href = '{$atiranyitas}';</script>";
                exit();
            }
            else echo('<span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: red; color:burlywood;margin: 0 auto">Legalább egy összetevőt kötelező megadni!</span>'); 
        }
        ?>    
        </div>
    </body>
</html>    
