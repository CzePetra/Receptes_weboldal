<!DOCTYPE html>
<html>
    <?php
    session_start();
    $_SESSION['oldal']='Regisztráció';
    include 'menu.php';
    ?>
    <head>
        <title>Regisztráció</title>
        <form action="regisztracio.php?tema=<?php echo $tema?>" method="post"> 
    </head>

    <body class="<?php echo $tema?>">
        <br/>
        <form action="regisztracio.php?tema=<?php echo $tema?>" method="post">
            <div class="text-center">        
            <input type="text" name="nev" class="btn btn-light" placeholder="Felhasználó név">
                <br/> <br/>
            <input type="email" name="email" class="btn btn-light" placeholder="Email cím">
                <br/> <br/>
            <input type="password" name="jelszo" class="btn btn-light" placeholder="Jelszó">  
                <br/> <br/>
            <input type="date" name="datum" class="btn btn-light" placeholder="Születésnap">
                <br/> <br/>
            <input type="submit" class="btn btn-<?php echo $tema?>" value="Regisztráció" name="regisztracio"> 
            <input type="reset" class="btn btn-<?php echo $tema?>"  value="Törlés">
            <br/> 
        </form>
    </body>

    <?php 

    if(isset($_POST['regisztracio']) and $_POST['jelszo'] and $_POST['email'] and $_POST['datum'] and $_POST['nev'])
    {
        include 'db.php';
        $link=getDb();

        $jelszo=mysqli_real_escape_string($link,$_POST['jelszo']);
        $nev=mysqli_real_escape_string($link,$_POST['nev']);
        $datum=mysqli_real_escape_string($link,$_POST['datum']);
        $felhasznalo_email=mysqli_real_escape_string($link,$_POST['email']);
        $mar_letezik=$link->query("SELECT email From Felhasznalo Where email='{$felhasznalo_email}'");

        if($mar_letezik=mysqli_fetch_object($mar_letezik))
            die ('<br/><span style=" font-size:30px;font-weight: bold;font-style: italic; background-color: grey;">
            A rendszer szerint Ön már regisztrált, ezért nem regisztrálhat újra!</span>');
        else
            $titkositott_jelszo=password_hash($jelszo, PASSWORD_DEFAULT);

        $insert_query = "INSERT INTO Felhasznalo (felhasznalo_id, nev, email, jelszo, szuletesnap, statusz) VALUES ('','$nev','$felhasznalo_email','$titkositott_jelszo','$datum','')";
        mysqli_query($link, $insert_query);
        
        echo '<br/><span class="btn btn-<?php echo $tema?>" style="background-color:thistle; color: slateblue">Sikeresen regisztrált!</span>';

        mysqli_close($link);
    }
    if(isset($_POST['regisztracio']) and (!$_POST['jelszo'] or !$_POST['email'] or !$_POST['datum'] or !$_POST['nev']))
        echo '<br/><span style=" font-size:30px;font-weight: bold;font-style: italic; background-color: grey;">Kérem adjon meg minden adatot a regisztrációhoz!</span>';
    ?>
    </div>
</html>
