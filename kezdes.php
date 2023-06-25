
<!DOCTYPE html>
<html>
  <?php
  session_start();
  $_SESSION['oldal']='Főoldal';
  include 'menu.php';
  ?>
  <head>
    <title>Recepteskönyv főoldal</title>
  </head>
  <body>
    <div class="text-center">
      <h1>Üdvözöljük az online recepteskönyv felületén! </h1> 
        <p>
          <h5 style="width: 85%; margin: 0 auto;">
              Az oldalon tárolhatja kedvenc receptjeit, az otthonában megtalálható hozzávalókat.
              <br/> 
              Kérhet recept javaslatot az otthoni alapanyagok felhasználásával <br/> és igényelhet bevásárló listát, 
              hogy elkészítse kedvenc receptjét.
              <br/>
              Megtekintheti a többi felhasználó receptjeit, adatait.
          </h5> 
        </p>
    </div> 
  </body>
</html>