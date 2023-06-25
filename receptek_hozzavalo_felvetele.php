<!DOCTYPE html>
<html>
    <?php
    session_start();
    $_SESSION['oldal']='Receptek';
    $_SESSION['atiranyitott_oldal']='receptek_hozzavalo_felvetele'; //mindenhol kell
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
        if(isset($_SESSION['id']))
        {
            $link=getDb();
            $id=$_SESSION['id'];
            $statusz=$link->query("SELECT statusz From Felhasznalo Where felhasznalo_id='{$id}'")->fetch_object()->statusz;
            mysqli_close($link); 
        }
        else die ('<span class="btn btn-<?php echo $tema?>" style="font-size:20px;font-weight: bold;font-style: italic; background-color: grey>HIBA történt a bejelentkezés közben, kérjük próbálja meg újra!</span>');

        if($statusz==0) die ('<div class="text-center"><br/><span class="btn btn-<?php echo $tema?>" style="font-size:20px;font-weight: bold;font-style: italic; background-color: grey">Sajnos Önnek nincs jogosultsága szerekezteni a receptek gyűjteményét. Probléma esetén keresse a koordinátort!</span><div>');
        else; //Receptet és hozzávalókat csak séf és koordinátor jogkörrel lehet felvenni
    ?>
    <head>
        <title>Recept felvétel</title>
    <form action="receptek_hozzavalo_felvetele.php?tema=<?php echo $tema?>" method="post"></form>
    </head>
    <body class="<?php echo $tema?>">
        <div style="width: 50%; float: left;">
            <br/>
            <form action="receptek_hozzavalo_felvetele.php?tema=<?php echo $tema?>" method="post">
                <input type="text" name="nev" class="btn btn-light" style="width: 500px" placeholder="Recept neve">
                    <br/> <br/>
                <input type="number" name="ido" class="btn btn-light" style="width: 500px" placeholder="Elkészítési idő (percben megadva)">
                    <br/> <br/>
                <input type="text" name="plusz" class="btn btn-light"  style="width: 500px" placeholder="Leírás/megjegyzés hozzáadása (nem kötelező)">  
                    <br/> <br/>
                <select name="szint" class="btn btn-light"  style="width: 500px">
                    <option value="">A recept nehézségét itt állíthatja be</option>
                    <option value="1">Extra kezdő</option>
                    <option value="2">Kezdő</option>
                    <option value="3">Közepes</option>
                    <option value="4">Haladóknak</option>
                    <option value="5">Átgondolandó</option>
                </select>
                <br/> <br/>
                <input type="submit" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Recept felvétele" name="felvett"> 
                <input type="reset" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Törlés"> 
            </form>
            <?php 
            if(isset($_POST['felvett']) and $_POST['nev'] and $_POST['ido'] and $_POST['szint'])
            {
                $link=getDb();
                $nev=mysqli_real_escape_string($link,$_POST['nev']);
                $ido=mysqli_real_escape_string($link,$_POST['ido']);
                $szint=mysqli_real_escape_string($link,$_POST['szint']);

                $mar_letezik=$link->query("SELECT nev From Recept Where nev='{$nev}'");

                if($mar_letezik=mysqli_fetch_object($mar_letezik))
                {
                    echo ('<span style="margin-left: 50px; font-size:20px;font-weight: bold;font-style: italic; background-color: grey;">Ez a recept már rögzítésre került!</span>');
                    mysqli_close($link); 
                }
                else
                {    
                    if($_POST['plusz']!==0) $plusz=mysqli_real_escape_string($link,$_POST['plusz']);
                    else $plusz=NULL;

                    $insert_query ="INSERT INTO Recept (recept_id, nev, time_min, megjegyzest, nehezsegi_szint) VALUES ('', '$nev', '$ido', '$plusz', '$szint')";
                    mysqli_query($link,$insert_query);

                    $id=$link->query("SELECT recept_id from Recept where nev='{$nev}'");
                    $row=mysqli_fetch_assoc($id);
                    $recept_id=(int) $row['recept_id'];

                    mysqli_close($link);
                    
                    $_SESSION['recept_id']=$recept_id;
                    header("Location: recept_megirasa.php?tema=$tema");
                }
            }  
            else if(isset($_POST['felvett']))
                echo('<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey;">
                        Egy új recept sikeres feltöltéséhez minden kötelező mezőt ki kell tölteni!</span>')
            ?>
        </div>
        <div style="width: 50%; float: right;">
            <br/>
            <form action="receptek_hozzavalo_felvetele.php?tema=<?php echo $tema?>" method="post">
                <input type="text" name="nev" class="btn btn-light" style="width: 500px" placeholder="Hozzávaló neve">

                <br/> <br/>
                <input type="submit" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Hozzávaló felvétele" name="kesz"> 
                <input type="reset" class="btn btn-<?php echo $tema?>" style="color: white; background-color:purple" value="Törlés"> 
            </form>
            <?php 
            if(isset($_POST['kesz']) and $_POST['nev'])
            {
                $link=getDb();
                $nev=mysqli_real_escape_string($link,$_POST['nev']);
                $mar_letezik=$link->query("SELECT nev From Hozzavalok Where nev='{$nev}'");

                if($mar_letezik=mysqli_fetch_object($mar_letezik))
                {
                    mysqli_close($link);
                    die ('<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey;">
                    Ezt a hozzávaló már bekerült az adatbázisba, ezért nem lehet újra felvenni!</span>');
                }
                else
                {
                    $insert_query ="INSERT INTO Hozzavalok (hozzavalo_id, nev) VALUES ('', '$nev')";
                    mysqli_query($link,$insert_query);
                    echo '<br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue;margin: 0 auto;">Sikeresen felvett hozzávaló</span>';
                    mysqli_close($link);
                }
            }  
            else if(isset($_POST['kesz']))
                echo('<br/><span class="btn btn-<?php echo $tema?>" style="font-size:15px;font-weight: bold;font-style: italic; background-color: grey;">
                    Egy új hozzávaló sikeres feltöltéséhez ki kell tölteni a mezőt!</span>')
            ?>
        </div>
    </body>
</html>