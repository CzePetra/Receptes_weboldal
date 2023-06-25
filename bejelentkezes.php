<!DOCTYPE html>
<html>
    <div class="text-center">
        <?php
        session_start();
        $_SESSION['oldal']='Bejelentkezés';
        include 'menu.php';
        include 'db.php';
        if(isset($_SESSION['id']))
        {
            if($_SESSION['bejelentkezve']==1)
            $id=$_SESSION['id'];
            if($id!==0)
            {
                $link=getDb();
                $nev=$link->query("SELECT nev from Felhasznalo where felhasznalo_id='{$id}'")->fetch_object()->nev;
                mysqli_close($link);
                
                echo '<br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue; font-size:20px">Ön ('.$nev.') már sikeresen bejelentkezett!</span>
                <br/><br/>
                <div  class="btn btn-'.$tema.'" style="color: white; background-color:thistle" >
                    <a class="nav-item nav-link" href="kijelentkezes.php?tema='.$tema.'">Kijelentkezés</a>
                </div>';
                die;
            }
        }
        ?>
        <head>
            <title>Bejelentkezes</title>
        </head>
        <body class="<?php echo $tema?>">
            <div class="text-center"> 
                <br/>
                <form action="bejelentkezes.php?tema=<?php echo $tema?>" method="post">              
                    <input type="email" name="email" class="btn btn-light" placeholder="Email cím" required>
                        <br/> <br/>
                    <input type="password" name="jelszo" class="btn btn-light" placeholder="Jelszó" required>  
                        <br/> <br/>
                    <input type="submit" class="btn btn-<?php echo $tema?>" value="Bejelentkezés" name="bejelentkezes"> 
                    <input type="reset" class="btn btn-<?php echo $tema?>"  value="Törlés"> 
                    <a href="regisztracio.php?tema=<?php echo $tema?>" class="btn btn-<?php echo $tema?>" role="button">Regisztráció</a>   
                </form>
        </body>

        <?php 
        if(isset($_POST['bejelentkezes']) and $_POST['jelszo'])
        {
            $link=getDb();
            $jelszo=mysqli_real_escape_string($link,$_POST['jelszo']);

            $felhasznalo_email=mysqli_real_escape_string($link,$_POST['email']);

            $kereses=$link->query("SELECT jelszo From Felhasznalo Where email='{$felhasznalo_email}'");
            if($kereses=mysqli_fetch_object($kereses))
            {
                $kodolt_jelszo=$link->query("SELECT jelszo From Felhasznalo Where email='{$felhasznalo_email}'")->fetch_object()->jelszo;
                if(password_verify($jelszo, $kodolt_jelszo))
                {
                    $id=$link->query("SELECT felhasznalo_id From Felhasznalo Where email='{$felhasznalo_email}'")->fetch_object()->felhasznalo_id;
                    $_SESSION['id']=$id;
                    $_SESSION['bejelentkezve']=1;
                    if(isset ($_SESSION['atiranyitott_oldal'])) $oldal=$_SESSION['atiranyitott_oldal'];
                    else $oldal='bejelentkezes';
                    header("Location: $oldal.php?tema=$tema");
                }
                else 
                {
                    mysqli_close($link);
                    die ('<br/><span style=" font-size:20px;font-weight: bold;font-style: italic; background-color: grey;">Hibás jelszó!</span>');
                }
            }        
            else 
            {
                mysqli_close($link);
                die('<br/><span style=" font-size:20px;font-weight: bold;font-style: italic; background-color: grey;">Nincs ilyen felhasználó, kérem regisztráljon!</span>');
            }
            mysqli_close($link);
        }
        ?>
    </div>
</html>
