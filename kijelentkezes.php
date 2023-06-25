<!DOCTYPE html>
<html>
  <?php 
  $_SESSION['oldal']='Kijelentkezés';
  include 'menu.php';
  session_start();
  session_destroy();
  $_SESSION['atiranyitott_oldal']='bejelentkezes';
  ?>
  <head>
    <title>Kijelentkezes</title>
  </head>
  <body>
    <div class="text-center">
        <p></p>
      <h1>Kijelentkezése sikeres volt! </h1> 
        <p></p>
      <h4>
          Reméljük nemsokára újra visszalátogat!
      </h4>  
    </div>
  </body>
</html>
