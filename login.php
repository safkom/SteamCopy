<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko"/>
	<meta name="author" content="Miha Šafranko" />
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/navbar.css">
    <title>Login</title>
</head>
<body>
<nav class="navbar">
        <div class="navbar-left">
            <b style = "color:white; font-family:'Courier New', Courier, monospace">SteamCopy</b>
        </div>
        <div class="navbar-center">
            <button class="center-button" onclick="location.href='index.php'">Store</button>
            <button class='center-button' onclick="location.href='library.php'">Library</button>
            <button class="center-button" onclick="location.href='community.php'">Community</button>
        </div>
        <div class="navbar-right">
        </div>
    </nav>
  <?php
  include_once 'libraries/vendor/autoload.php';
  session_start();
  if(isset($_SESSION['id'])){
    setcookie('prijava', "Opla! Si že prijavljen, ne rabiš biti tukaj.");
    setcookie('warning', 1);
    header('Location: index.php');
    exit();
  }
  $google_client = new Google_Client();

$google_client->setClientId('512131787454-n3nrrf6flttgsle6l2903od7mp1v58so.apps.googleusercontent.com');

$google_client->setClientSecret('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');

$google_client->SetRedirectUri('https://safko.eu/steamcopy/googlelogin.php');

$google_client->addScope('email');

$google_client->addScope('profile');
  ?>
  <div class='content-below-navbar'>
  <br>
  <div class = "container">
    <h1>Prijava</h1>
 <form action="preveri.php" method="post">
  <label for="email">Mail:</label>
  <input type="text" id="email" name="email" required><br><br>
  <label for="geslo">Geslo:</label>
  <input type="password" id="geslo" name="geslo" required><br><br>
  <input type="submit" value="Pošlji">
</form>
<p>Še nisi uporabnik? <a href = "registracija.php">Pojdi na registracijo</a>

<p>Lahko se tudi prijaviš z Google računom: <a href = "<?php echo $google_client->createAuthUrl()?>">Prijavi se z Google računom</a>
  </div>
  <?php
  include_once "alert.php";
?>
</body>
</html>