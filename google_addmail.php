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
  session_start();
  if(isset($_SESSION['id'])){
    setcookie('prijava', "Opla! Si že prijavljen, ne rabiš biti tukaj.");
    setcookie('warning', 1);
    header('Location: index.php');
    exit();
  }
  $_SESSION['googleregister'] = 1;
  ?>
  <br>
  <div class = "container">
    <h1>Google povezava</h1>
    <p>Poveži svoj trenutni račun, z svojim google računom za lažjo prijavo:</p>
 <form action="googlelink.php" method="post">
 <label for="email">Mail:</label>
  <input type="text" id="email" name="email" required readonly value = "<?php echo $_SESSION['mail'] ?>"><br><br>
  <label for="geslo">Geslo:</label>
  <input type="password" id="geslo" name="geslo" required><br><br>
  <input type="submit" value="Pošlji">
</form>
<p>Nočeš povezati računa?<a href = "login.php"> Pojdi na prijavo</a>
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
<?php
include_once "alert.php";
?>
</body>
</html>