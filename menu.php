<!DOCTYPE html>
<html>
  <?php
  include 'tema.php';
  $tema=get_tema();
  $oldal=$_SESSION['oldal']
  ?>
  <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="stilus.css">
  </head>

  <body class="<?php echo $tema?>">
    <nav class="navbar navbar-expand-lg navbar-<?php echo $tema?> bg-<?php echo $tema?>">
      <a class="navbar-brand" href="#"><?php echo $oldal?></a>
        <div class="navbar-nav" text=center>
          <a class="nav-item nav-link" href="kezdes.php">Főoldal</a>
          <a class="nav-item nav-link" href="receptek.php?tema=<?php echo $tema?>">Recepteskönyv</a>
          <a class="nav-item nav-link" href="bejelentkezes.php?tema=<?php echo $tema?>">Bejelentkezés</a>
          <a class="nav-item nav-link" href="otthon.php?tema=<?php echo $tema?>">Otthon</a>
          <a class="nav-item nav-link" href="statusz.php?tema=<?php echo $tema?>">Státusz beállítás</a>
          <a class="nav-item nav-link" href="?tema=light">Édes téma</a> 
          <a class="nav-item nav-link" href="?tema=dark">Sós téma</a>
        </div>
    </nav>
  </body>
</html>