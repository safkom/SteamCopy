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
$_SESSION['lastlocation']="admin_library.php";
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
    <div class='content-below-navbar'>
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
        $zanr_id = $row['zanr_id'];
        $user_id = $row['uporabnik_id'];
        $file = $row['file_url'];

        $sql = "SELECT * FROM zanri WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$zanr_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $zanr = $row['ime'];

        echo "<div class='user'>";
        echo "<div class='user-info'>";
        echo "<p><b>$ime</b></p><br>";
        echo "<p>$opis</p><br>";
        echo "<p>$zanr</p><br>";
        $sql2 = "SELECT * FROM uporabniki WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$user_id]);
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $username = $row2['username'];
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
        $zanr_id = $row['zanr_id'];
        $user_id = $row['uporabnik_id'];
        $file = $row['file_url'];


        $sql = "SELECT * FROM zanri WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$zanr_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $zanr = $row['ime'];

        echo "<div class='user'>";
        echo "<div class='user-info'>";
        echo "<p><b>$ime</b></p><br>";
        echo "<p>$opis</p><br>";
        echo "<p>$zanr</p><br>";
        $sql2 = "SELECT * FROM uporabniki WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$user_id]);
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $username = $row2['username'];
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
        $zanr_id = $row['zanr_id'];
        $file = $row['file_url'];

        $sql = "SELECT * FROM zanri WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$zanr_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $zanr = $row['ime'];

        echo "<div class='user'>";
        echo "<div class='user-info'>";
        echo "<p><b>$ime</b></p><br>";
        echo "<p>$opis</p><br>";
        echo "<p>$zanr</p><br>";
        echo "<button class='download-button' type='submit' onclick=\"window.open('".$file."')\">Prenesi igro</button> ";
        echo "<button class='delete-button' onclick=\"location.href='deletegame.php?id=".$game_id."'\">Odstrani iz trgovine</button>";
        echo "</div>";
        echo "</div>";
        echo "<div class='content-below-navbar'>";
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
  if(isset($_SESSION['id']) == false) return false;
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
