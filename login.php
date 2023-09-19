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
  $google_client = new Google_Client();

$google_client->setClientId('512131787454-n3nrrf6flttgsle6l2903od7mp1v58so.apps.googleusercontent.com');

$google_client->setClientSecret('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');

$google_client->SetRedirectUri('http://safko.eu/steamcopy/login.php');

$google_client->addScope('email');

$google_client->addScope('profile');
  ?>
  <br>
  <div class = "container">
    <h1>Prijava</h1>
 <form action="preveri.php" method="post">
  <label for="email">Mail:</label>
  <input type="text" id="email" name="email" required><br><br>
  <label for="geslo">Geslo:</label>
  <input type="password" id="geslo" name="geslo" required><br><br>
</datalist>
  <input type="submit" value="Pošlji">
</form>
<p>Še nisi uporabnik? <a href = "registracija.php">Pojdi na registracijo</a>

<p>Lahko se tudi prijaviš z Google računom: <a href = "<?php echo $google_client->createAuthUrl()?>">Prijavi se z Google računom</a>
  </div>
  <div id="loginWindow">
    <?php
    if (isset($_COOKIE['prijava'])) {
        echo "✅ ";
        echo $_COOKIE['prijava'];
        // setcookie("prijava", "", time() - 3600);
    }
    ?>
</div>
<div id="errorWindow">
    <?php
    if (isset($_COOKIE['prijava'])) {
        echo "⛔ ";
        echo $_COOKIE['prijava'];
        // setcookie("prijava", "", time() - 3600);
    }
    ?>
</div>
<div id="warningWindow">
    <?php
    if (isset($_COOKIE['prijava'])) {
        echo "⚠️ ";
        echo $_COOKIE['prijava'];
        // setcookie("prijava", "", time() - 3600);
    }
    ?>
</div>

  <script>
  document.getElementById("errorWindow").style.display = "none";
document.getElementById("warningWindow").style.display = "none";
document.getElementById("loginWindow").style.display = "none";

// Check if cookie error is set to 1
if (getCookie("error") === "1") {
  document.getElementById("errorWindow").style.display = "block";
  setTimeout(function() {
    document.getElementById("errorWindow").style.display = "none";
  }, 5000); // Hide errorWindow after 5 seconds (adjust the time as needed)
}
// Check if cookie warning is set to 1
else if (getCookie("warning") === "1") {
  document.getElementById("warningWindow").style.display = "block";
  setTimeout(function() {
    document.getElementById("warningWindow").style.display = "none";
  }, 5000); // Hide warningWindow after 5 seconds (adjust the time as needed)
}
// If neither cookie is set to 1, show loginWindow
else if (getCookie("good") === "1"){
  document.getElementById("loginWindow").style.display = "block";
  setTimeout(function() {
    document.getElementById("loginWindow").style.display = "none";
  }, 5000); // Hide loginWindow after 5 seconds (adjust the time as needed)
}

// Function to get cookie value by name
function getCookie(name) {
  const cookies = document.cookie.split("; ");
  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i].split("=");
    if (cookie[0] === name) {
      return cookie[1];
    }
  }
  return "";
}

    document.cookie = 'prijava=; Max-Age=0';
    document.cookie = 'error=; Max-Age=0';
    document.cookie = 'warning=; Max-Age=0';
    document.cookie = 'good=; Max-Age=0';
</script>
</body>
</html>