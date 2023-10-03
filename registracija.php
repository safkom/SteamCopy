<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko"/>
	<meta name="author" content="Miha Šafranko" />
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/navbar.css">
    <title>Registracija</title>
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
    <h1>Registracija</h1>
 <form action="register.php" method="post">
  <label for="ime">Ime:</label>
  <input type="text" id="ime" name="ime" required><br><br>
  <label for="priimek">Priimek:</label>
  <input type="text" id="priimek" name="priimek" required><br><br>
  <label for="username">Username:</label>
  <input type="text" id="username" name="username" required><br><br>
  <label for="email">Mail:</label>
  <input type="text" id="email" name="email" required><br><br>
  <label for="geslo">Geslo:</label>
  <input type="password" id="geslo" name="geslo" required><br><br>
</datalist>
  <input type="submit" value="Pošlji">
</form>
<p>Ste že uporabnik? <a href = "login.php">Pojdite na prijavo</a>

<p>Lahko se tudi registriraš z Google računom: <a href = "<?php echo $google_client->createAuthUrl()?>">Registriraj se z Google računom</a>
  </div>
  <div id="loginWindow">
    <?php
 include_once "alert.php";
 ?>
</body>
</html>