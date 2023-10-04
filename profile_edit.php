<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko"/>
	<meta name="author" content="Miha Šafranko" />
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/navbar.css">
    <title>Edit</title>
</head>
<?php
session_start();
require_once 'connect.php';
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Za dostop do te strani, se prijavi.");
    setcookie('warning', 1);
    header('Location: prijava.php');
    exit();
}
if (isBanned($conn)) {
  session_destroy();
  setcookie('prijava', 'Vaš račun je blokiran.');
  setcookie('error', 1);
  header("Location: login.php");
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
        $isAdmin = isUserAdmin($conn);
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
            $sql = "SELECT * FROM uporabniki WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $_SESSION['id'], PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $opis = $row['opis'];
            $slika_id = $row['slika_id'];

            $sql1 = "SELECT * FROM slike WHERE id = :slika_id";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindParam(":slika_id", $slika_id, PDO::PARAM_INT);
            $stmt1->execute();
            $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            $url = $row1['url'];
            ?>
        </div>
    </nav>
    <div class='content-below-navbar'>
    <br>
    <div class="container">
    <h1>Uredi profil</h1>
    <form action="uredi.php" method="post" enctype="multipart/form-data">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required value = "<?php echo $_SESSION['username'] ?>"><br><br>
    <label for="opis">Opis:</label>
    <input type="text" id="opis" name="opis" value = "<?php echo $opis ?>"><br><br>
  
    <p>Trenutna slika:</p>
    <img src="<?php echo $url ?>" alt="profile picture of <?php echo $_SESSION['username'] ?>" width="100" height="100"><br><br>
    <label for="slika">Slika:</label>
    <input type="file" name="slika" id="slika"> <br><br>
    <input type="submit" value="Shrani">
    </form>
    <br>
    <button id='user-button' onclick="location.href='profile.php'">Nazaj</button>
    
  </div>

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
