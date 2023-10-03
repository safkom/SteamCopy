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
session_start();
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Za dostop do te strani, se prijavi.");
    setcookie('warning', 1);
    header('Location: index.php');
    exit();
}
$_SESSION['lastlocation']="friends.php";
require_once 'connect.php';
$isAdmin = isUserAdmin($conn);

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
        <h1>Friend List</h1>
        <?php
require_once 'connect.php';

//check if you have any friends
$sql = "SELECT * FROM friends WHERE (user_id = ? OR requester_id = ?)AND prosnja_sprejeta = 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['id'], $_SESSION['id']]);
$num_rows = $stmt->rowCount();
if($num_rows != 0){
// First, select friends where the user is the recipient
$sql = "SELECT * FROM friends WHERE user_id = ? AND prosnja_sprejeta = 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['id']]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Retrieve friend's information
    $friend_id = $row['requester_id'];
    $friendInfo = getUserInfo($conn, $friend_id);

    // Display friend's information
    displayUserInfo($conn, $friendInfo);
}

// Then, select friends where the user is the requester
$sql = "SELECT * FROM friends WHERE requester_id = ? AND prosnja_sprejeta = 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['id']]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Retrieve friend's information
    $friend_id = $row['user_id'];
    $friendInfo = getUserInfo($conn, $friend_id);

    // Display friend's information
    displayUserInfo($conn, $friendInfo);
}
}
else{
    echo "<p>Nimaš prijateljev.</p>";
}

function getUserInfo($conn, $user_id) {
    $sql = "SELECT * FROM uporabniki WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function displayUserInfo($conn, $user) {
    $id = $user['id'];
    $username = $user['username'];
    $opis = $user['opis'];
    $slika_id = $user['slika_id'];
    $user_id = $user['id'];
    
    $sql1 = "SELECT * FROM slike WHERE id = :slika_id";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bindParam(":slika_id", $slika_id, PDO::PARAM_INT);
    $stmt1->execute();
    $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $url = $row1['url'];

    echo "<div class='user'>";
    echo "<img src='$url' alt='Avatar' width='100' height='100' class='avatar'>";
    echo "<div class='user-info'>";
    echo "<p><b>$username</b></p>";
    echo "<p>$opis</p>";
    echo "<button class='profile-button' onclick=\"location.href='profiles.php?id=".$user_id."'\">Oglej profil</button>  ";
    echo "<button class='profile-button' onclick=\"location.href='deletefriend.php?id=".$user_id."'\">Odstrani prijatelja</button>";
    echo "</div>";
    echo "</div>";
}
?>


    
</div>

  <?php
    // if num rows = 0, then dont show div
    $sql = "SELECT * FROM friends WHERE user_id = ? AND prosnja_sprejeta = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    $num_rows = $stmt->rowCount();
    if($num_rows > 0){
      echo "<div id='container'>";
      echo "<h1>Prejete Prošnje</h1>";
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Retrieve data for each row
        $user_id = $row['requester_id'];
        $sql1 = "SELECT * FROM uporabniki WHERE id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$user_id]);
        $innerRow = $stmt1->fetch(PDO::FETCH_ASSOC);
    
        $id = $innerRow['id'];
        $username = $innerRow['username'];
        $opis = $innerRow['opis'];
        $slika_id = $innerRow['slika_id'];
        
        // Retrieve image URL
        $sql2 = "SELECT * FROM slike WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$slika_id]);
        $row1 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $url = $row1['url'];
    
        // Display the user information
        echo "<div class='user'>";
        echo "<img src='$url' alt='Avatar' width='100' height='100' class='avatar'>";
        echo "<div class='user-info'>";
        echo "<p><b>$username</b></p>";
        echo "<p>$opis</p>";
        echo "<button class='profile-button' onclick=\"location.href='acceptrequest.php?profile_id=".$user_id."'\">Sprejmi prošnjo</button>  ";
        echo "<button class='profile-button' onclick=\"location.href='cancelrequest.php?profile_id=".$user_id."'\">Zbriši prošnjo</button>";
        echo "</div>";
        echo "</div>";
    }
  }
?>

</div>


<?php 
    $sql = "SELECT * FROM friends WHERE requester_id = ? AND prosnja_sprejeta = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    $num_rows = $stmt->rowCount();
    if($num_rows > 0){
      echo "<div id='container'>";
      echo "<h1>Prejete Prošnje</h1>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Retrieve data for each row
    $user_id = $row['user_id'];
    $sql1 = "SELECT * FROM uporabniki WHERE id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([$user_id]);
    $innerRow = $stmt1->fetch(PDO::FETCH_ASSOC);

    $id = $innerRow['id'];
    $username = $innerRow['username'];
    $opis = $innerRow['opis'];
    $slika_id = $innerRow['slika_id'];
    
    // Retrieve image URL
    $sql2 = "SELECT * FROM slike WHERE id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute([$slika_id]);
    $row1 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $url = $row1['url'];

    // Display the user information
    echo "<div class='user'>";
    echo "<img src='$url' alt='Avatar' width='100' height='100' class='avatar'>";
    echo "<div class='user-info'>";
    echo "<p><b>$username</b></p>";
    echo "<p>$opis</p>";
    echo "<button class='profile-button' onclick=\"location.href='cancelrequest.php?profile_id=".$user_id."'\">Prekliči prošnjo</button>";
    echo "</div>";
    echo "</div>";
    }}
// Display rows from the second query
?>





<?php
function isUserAdmin($conn) {
  if(isset($_SESSION['id']) == false) return false; // If user is not logged in, return false (not admin']))
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

function userloggedIn(){
    if(isset($_SESSION['username'])){
        return true;
    }
    else{
        return false;
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
