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
require_once 'connect.php';
$isAdmin = isUserAdmin($conn);
$_SESSION['lastlocation']="profiles_admin.php?id=".$_GET['id']."";
if(!isset($_GET['id'])){
    header('Location: community.php');
    exit();
}
if(!$isAdmin){
  setcookie('prijava', "Tu nimaš dostopa.");
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
                echo "<button class='user-button' onclick=\"location.href='friends.php'\">Friends</button>";
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
    <?php
    require_once 'connect.php';

    $profile_id = $_GET['id'];

    // Prepare the first SQL statement to retrieve slika_id
    $sql1 = "SELECT * FROM uporabniki WHERE id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([$profile_id]);
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $slika_id = $result1['slika_id'];
    $username = $result1['username'];
    $opis = $result1['opis'];

    $sql2 = "SELECT url FROM slike WHERE id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute([$slika_id]);
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $slika = $result2['url'];
    echo "<img src='" . $slika . "' alt='profile picture of " .$username . "' width='100' height='100'>";
    
    echo "<br><br>";

    echo $username;
    echo "<br><br>";
    if($opis != null){
        echo "<p>".$opis."</p>";
    }

    if(userLoggedIn() && $profile_id != $_SESSION['id']){
        $sql1 = "SELECT * FROM friends WHERE requester_id = ? AND user_id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$_SESSION['id'], $profile_id]);

        // Check if there are any rows returned
        if ($stmt1->rowCount() > 0) {
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

    // Check the value of the 'prosnja_sprejeta' column and cast it to an integer
    $prosnja_sprejeta = (int)$result1['prosnja_sprejeta'];

    // Define the $profile_id variable (replace 'YOUR_PROFILE_ID' with the actual value)

    // Display the appropriate button based on the 'prosnja_sprejeta' value
    if ($prosnja_sprejeta === 1) {
        echo "<button class='profile-button' onclick=\"location.href='cancelfriend.php?profile_id=".$profile_id."'\">Odstrani prijatelja</button>  ";
        echo "<button class='delete-button' onclick=\"location.href='banuser.php?profile_id=".$profile_id."'\">Blokiraj račun</button>";
    } elseif ($prosnja_sprejeta === 0) {
        echo "<button class='profile-button' onclick=\"location.href='cancelfriend.php?profile_id=".$profile_id."'\">Prekliči zahtevo</button>  ";
        echo "<button class='delete-button' onclick=\"location.href='banuser.php?profile_id=".$profile_id."'\">Blokiraj račun</button>";
    }
}  
    }

    if(userLoggedIn() && $profile_id != $_SESSION['id']){
        $sql1 = "SELECT * FROM friends WHERE requester_id = ? AND user_id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$profile_id, $_SESSION['id']]);

        // Check if there are any rows returned
        if ($stmt1->rowCount() > 0) {
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

    // Check the value of the 'prosnja_sprejeta' column and cast it to an integer
    $prosnja_sprejeta = (int)$result1['prosnja_sprejeta'];

    // Define the $profile_id variable (replace 'YOUR_PROFILE_ID' with the actual value)

    // Display the appropriate button based on the 'prosnja_sprejeta' value
    if ($prosnja_sprejeta === 1) {
        echo "<button class='profile-button' onclick=\"location.href='cancelfriend.php?profile_id=".$profile_id."'\">Odstrani prijatelja</button>  ";
        echo "<button class='delete-button' onclick=\"location.href='banuser.php?profile_id=".$profile_id."'\">Blokiraj račun</button>";
    } elseif ($prosnja_sprejeta === 0) {
        echo "<button class='profile-button' onclick=\"location.href='cancelfriend.php?profile_id=".$profile_id."'\">Zavrni zahtevo</button>  ";
        echo "<button class='delete-button' onclick=\"location.href='banuser.php?profile_id=".$profile_id."'\">Blokiraj račun</button>";
    }
} 
    }

    if(userLoggedIn() && $profile_id != $_SESSION['id']){
        $sql1 = "SELECT * FROM friends WHERE (requester_id = ? AND user_id = ?) OR (user_id = ? AND requester_id = ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$_SESSION['id'], $profile_id, $_SESSION['id'], $profile_id]);

        // Check if there are any rows returned
        if ($stmt1->rowCount() == 0) {
            echo "<button class='profile-button' onclick=\"location.href='addfriend.php?profile_id=".$profile_id."'\">Dodaj prijatelja</button>  ";
            echo "<button class='delete-button' onclick=\"location.href='banuser.php?profile_id=".$profile_id."'\">Blokiraj račun</button>";
        }
    }
    echo "<br><br>";
    echo "<h2>Mnenja uporabnikov</h2>";
    if(userloggedIn()){
      echo "Dodaj komentar: <br>";
      echo "<form action='addkomentar.php?id=".$profile_id."' method='post'>";
      //dodaj dva boxa če je mnenje pozitivno ali negativno
      echo "<textarea name='mnenje' rows='5' cols='40'></textarea><br>";
      echo "<button class='download-button' type='submit'>Oddaj komentar</button>";
      echo "</form>";
      echo "<br>";
  }

    //prikaži komentarje uporabnikov
    $sql = "SELECT * FROM komentarji WHERE profil_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$profile_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($result != null) {
    foreach ($result as $row) {
        echo "<div class='user'>";
        $user_id = $row['pisatelj_id'];
        $mnenje = $row['text'];
        
        // Use the correct variable name $result2 instead of $result
        $sql2 = "SELECT * FROM uporabniki WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$user_id]);
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        // Use $result2['username'] instead of $result['username']
        $username2 = $result2['username'];
        
        // Use $result2['slika_id'] instead of $result['slika_id']
        $slika_id2 = $result2['slika_id'];
        
        $sql_slika = "SELECT * FROM slike WHERE id = ?";
        $stmt_slika = $conn->prepare($sql_slika);
        $stmt_slika->execute([$slika_id2]); // Use $slika_id2
        $result_slika = $stmt_slika->fetch(PDO::FETCH_ASSOC);
        $slika = $result_slika['url'];

        echo "<button class='delete-mnenje-button' onclick=\"location.href='deletekomentaradmin.php?id=" . $row['id'] . "'\">Odstrani mnenje</button>";
        echo "<img src='" . $slika . "' alt='slika uporabnika' height='100px' width='100px'>
        <p><b>" . $username2 . ": </b><br>"; // Use $username2
        echo $mnenje . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Uporabnik še nima mnenj.</p>";
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
include_once "alert.php";
?>
</body>
</html>
