<?php
function getDB()
{    
//kapcsolat teremtés
    $link = mysqli_connect("localhost", "root", "", "hfdb")
        or die ("Kapcsolati hiba" . mysqli_error());
//adatbázis kiválasztása
    mysqli_select_db($link,"hfdb");
    return $link;  
}
?>