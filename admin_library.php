<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko">
    <meta name="author" content="Miha Šafranko">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel ="stylesheet" type ="text/css"href="css/navbar.css">
    <link rel ="stylesheet" type ="text/css"href="css/profile.css">
    <title>SteamCopy</title>  
</head>
<?php
require_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Tu nimaš dostopa.");
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}
$isAdmin = isUserAdmin($conn);
if (!$isAdmin) {
  setcookie('prijava', "Tu nimaš dostopa.". $isAdmin);
  setcookie('error', 1);
  header('Location: index.php');
  exit();
}
if (isBanned($conn)) {
  session_destroy();
  setcookie('prijava', 'Vaš račun je blokiran.');
  setcookie('error', 1);
  header("Location: index.php");
  die();
}
?>

<body> 
<nav class="navbar">
        <div class="navbar-left">
            <b style = "color:white; font-family:'Courier New', Courier, monospace">SteamCopy</b>
        </div>
        <div class="navbar-center">
        <?php
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
            if (userLoggedIn()) {
                echo "<button class='user-button' onclick=\"location.href='profile.php'\">" . $_SESSION['username'] . "</button>";
                echo "<button class='user-button' onclick=\"location.href='odjava.php'\">Logout</button>";
            } else {
                echo "<button class='user-button' onclick=\"location.href='login.php'\">Login</button>";
                echo "<button class='user-button' onclick=\"location.href='registracija.php'\">Register</button>";
            }
            
            ?>
        </div>
    </nav>
    <br>
    <div id="container">
        <h1>Vse igre</h1>
    <?php
    require_once 'connect.php';
    $sql = "SELECT * FROM igre";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $game_id = $row['id'];
        $ime = $row['ime'];
        $opis = $row['opis'];
        $zanr = $row['zanr'];
        $user_id = $row['uporabnik_id'];
        $file = $row['file_url'];
        echo "<div class='user'>";
        echo "<div class='user-info'>";
        echo "<p><b>$ime</b></p><br>";
        echo "<p>$opis</p><br>";
        echo "<p>$zanr</p><br>";
        $sql = "SELECT * FROM uporabniki WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $username = $row['username'];
        echo "<p><b>Ustvarjalec: </b></p>";
        echo "<button class='profile-button' onclick=\"location.href='profiles.php?id=".$user_id."'\">".$username."</button><br><br>";
        echo "<button class='download-button' type='submit' onclick=\"window.open('".$file."')\">Prenesi igro</button> ";
        echo "<button class='delete-button' onclick=\"location.href='deletegame.php?id=".$game_id."'\">Zbriši igro iz trgovine</button>";
        echo "</div>";
        echo "</div>";
    }
    ?>
    <div id="container">
        <h1>Kupljene igre</h1>
    <?php
    require_once 'connect.php';
    $sql = "SELECT * FROM nakupi WHERE uporabnik_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $sql = "SELECT * FROM igre WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$row['igra_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        $game_id = $row['id'];
        $ime = $row['ime'];
        $opis = $row['opis'];
        $zanr = $row['zanr'];
        $user_id = $row['uporabnik_id'];
        $file = $row['file_url'];
        echo "<div class='user'>";
        echo "<div class='user-info'>";
        echo "<p><b>$ime</b></p><br>";
        echo "<p>$opis</p><br>";
        echo "<p>$zanr</p><br>";
        $sql = "SELECT * FROM uporabniki WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $username = $row['username'];
        echo "<p><b>Ustvarjalec: </b></p>";
        echo "<button class='profile-button' onclick=\"location.href='profiles.php?id=".$user_id."'\">".$username."</button><br><br>";
        echo "<button class='download-button' type='submit' onclick=\"window.open('".$file."')\">Prenesi igro</button> ";
        echo "<button class='delete-button' onclick=\"location.href='deletegameuser.php?id=".$game_id."'\">Odstrani iz kupljenih iger</button>";
        echo "</div>";
        echo "</div>";
    }
    ?>
    
</div>
<div id="container">
    <h1>Objavljene igre</h1>
    <?php 
    $sql = "SELECT * FROM igre WHERE uporabnik_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $game_id = $row['id'];
        $ime = $row['ime'];
        $opis = $row['opis'];
        $zanr = $row['zanr'];
        $file = $row['file_url'];
        echo "<div class='user'>";
        echo "<div class='user-info'>";
        echo "<p><b>$ime</b></p><br>";
        echo "<p>$opis</p><br>";
        echo "<p>$zanr</p><br>";
        echo "<button class='download-button' type='submit' onclick=\"window.open('".$file."')\">Prenesi igro</button> ";
        echo "<button class='delete-button' onclick=\"location.href='deletegame.php?id=".$game_id."'\">Odstrani iz trgovine</button>";
        echo "</div>";
        echo "</div>";
    }
?>
</div>
<?php
require_once 'connect.php';
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

    if (isset($_COOKIE['prijava'])) {
       echo "<div id='loginWindow'>";
        echo "✅ ";
        echo $_COOKIE['prijava'];
        // setcookie("prijava", "", time() - 3600);
        echo "</div>";
    }
    ?>


    <?php
    
    if (isset($_COOKIE['prijava'])) {
        echo "<div id='errorWindow'>";
        echo "⛔ ";
        echo $_COOKIE['prijava'];
        // setcookie("prijava", "", time() - 3600);
        echo "</div>";
    }
    ?>


    <?php
    if (isset($_COOKIE['prijava'])) {
        echo "<div id='warningWindow'>";
        echo "⚠️ ";
        echo $_COOKIE['prijava'];
        // setcookie("prijava", "", time() - 3600);
        echo "</div>";
    }
    ?>
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
<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

</body>
</html>
