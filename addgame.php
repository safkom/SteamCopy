<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko"/>
	<meta name="author" content="Miha Šafranko" />
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/navbar.css">
    <title>Game Upload</title>
</head>
<body>
<nav class="navbar">
        <div class="navbar-left">
            <b style = "color:white; font-family:'Courier New', Courier, monospace">SteamCopy</b>
        </div>
        <div class="navbar-center">
          <?php
          session_start();
          require_once 'connect.php';
          $isAdmin = isUserAdmin($conn);
          if (isBanned($conn)) {
            session_destroy();
            setcookie('prijava', 'Vaš račun je blokiran.');
            setcookie('error', 1);
            header("Location: index.php");
            die();
          }
          if($isAdmin){
            echo "<button class='center-button' onclick=\"location.href='index.php'\">Store</button>";
            echo "<button class='center-button' onclick=\"location.href='admin_library.php'\">Library</button>";
            echo "<button class='center-button' onclick=\"location.href='community_admin.php'\">Community</button>";
          }
          else{
            echo "<button class='center-button' onclick=\"location.href='index.php'\">Store</button>";
            echo "<button class='center-button' onclick=\"location.href='library.php'\">Library</button>";
            echo "<button class='center-button' onclick=\"location.href='community.php'\">Community</button>";
          }
            ?>
        </div>
        <div class="navbar-right">
            <?php
            if (!isset($_SESSION['id'])) {
              setcookie('prijava', "Za to stran se rabiš prijaviti.");
              setcookie('warning', 1);
              header('Location: index.php');
              exit();
          }
            if (userLoggedIn()) {
                echo "<button class='user-button' onclick=\"location.href='friends.php'\">Friends</button>";
                echo "<button class='selected-button' onclick=\"location.href='profile.php'\">" . $_SESSION['username'] . "</button>";
                echo "<button class='user-button' onclick=\"location.href='odjava.php'\">Logout</button>";
            } else {
                echo "<button class='user-button' onclick=\"location.href='login.php'\">Login</button>";
                echo "<button class='user-button' onclick=\"location.href='registracija.php'\">Register</button>";
            }
            
            ?>
        </div>
    </nav>
  <?php
  ?>
  <br>
  <div class = "container">
    <h1>Game Upload</h1>
 <form action="upload.php" method="post" enctype="multipart/form-data">
  <label for="ime">Ime igre:</label>
  <input type="text" id="ime" name="ime" required><br><br>
  <label for="cena">Cena igre:</label>
  <input type="number" id="cena" name="cena" required step="0.01" min="0"><br><br>
  <label for="ime">Žanr igre:</label>
  <input type="text" id="zanr" name="zanr" required><br><br>
  <label for="opis">Opis igre:</label>
  <textarea id="opis" name="opis" rows="4" cols="50" required></textarea>
  <label for="zip">Datoteke igre:</label>
  <input type="file" id="zip" name="zip" required><br><br>
  <label for="email">Slike igre:</label>
  <input type="file" id="slika" name="slika[]" required multiple><br><br>
  <input type="submit" value="Pošlji">
</form>
<br>
    <button id='user-button' onclick="location.href='index.php'">Nazaj</button>
  </div>
  <div id="loginWindow">
    <?php

function userloggedIn(){
    if(isset($_SESSION['username'])){
        return true;
    }
    else{
        return false;
    }
}

function isUserAdmin($conn) {
  $sql = "SELECT * FROM uporabniki WHERE id = ? AND admin = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$_SESSION['id']]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if ($result == false) {
    return false;
  } else {
    return true;
  }
}

function isBanned($conn) {
  if(!isset($_SESSION['id'])) return false; // If user is not logged in, return false (not banned
  $sql = "SELECT * FROM uporabniki WHERE id = ? AND banned = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$_SESSION['id']]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if ($result == false) {
    return false;
  } else {
    return true;
  }
}
include_once "alert.php";
?>
</body>
</html>