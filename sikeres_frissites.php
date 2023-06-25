<!DOCTYPE html>
<html>
    <?php
    session_start();
    $cim=$_SESSION['oldal'];
    $hozzavalo='Alapanyagok felvétele';
    $recept='Kedvenc receptek kiválasztása';
    include 'menu.php';
    include 'db.php';
    ?>
    <head>
        <title>Recept</title>
    </head>
    <body>
        <div class="text-center"> 
            <h5>
                <p>
                    <?php
                    if($_SESSION['rogzult_hozzavalo']==1) echo 'Sikeresen rögzítette a kiválasztott hozzávalókat!';
                    else if ($_SESSION['rogzult_receptek']==1) echo "Sikeresen rögzítette a kiválasztott recepteket!";
                    else echo 'A korábbiakban nem választott ki egyetlen mezőt sem, ha szeretne valamit rögzíteni lépjen vissza a gombbal!';
                    
                    if($cim==$hozzavalo) 
                    {
                        echo'
                        <br/>
                        <form action="feltoltes.php?tema='.$tema.'" method="post">
                            <input type="submit" name="vissza_a_feltolteshez" class="btn btn-'.$tema.'" style="color: black; background-color:lightblue" value="Vissza az előző oldalra" >
                        </form>';
                    }

                    if($cim==$recept)
                    {
                        echo'
                        <br/>
                        <form action="kedvencek_kivalasztasa.php?tema='.$tema.'" method="post">
                            <input type="submit" name="vissza_a_feltolteshez" class="btn btn-'.$tema.'" style="color: black; background-color:lightblue" value="Vissza az előző oldalra" >
                        </form>';            
                    }
                    ?>
                </p>
            </h5>
        </div>   
    </body>
</html>