<?php
function get_tema() //A sós témát a dark "kódolja" a sütis téma a light
{
    if (isset($_GET['tema'])) // Ellenőrizzük, hogy beállították-e a téma módját
    {
        $_SESSION['tema'] = $_GET['tema']; // Téma mód beállítása a munkamenetben
    }

    if (isset($_SESSION['tema'])) // Ellenőrizzük, hogy beállították-e a téma módot a munkamenetben
    {
        $tema= $_SESSION['tema']; // Használjuk a téma módot a munkamenetből
    } 
    else 
    {
        $tema = 'light'; // Alapértelmezettként a világos téma lesz beállítva
    }
    return $tema;
}
?>