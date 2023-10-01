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
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}
$isAdmin = isUserAdmin($conn);
$_SESSION['lastlocation']="profile.php";

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
                echo "<button class='selected-button' onclick=\"location.href='profile.php'\">" . $_SESSION['username'] . "</button>";
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

    // Prepare the first SQL statement to retrieve slika_id
    $sql1 = "SELECT slika_id FROM uporabniki WHERE id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([$_SESSION['id']]);
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $slika_id = $result1['slika_id'];

    $sql2 = "SELECT url FROM slike WHERE id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute([$slika_id]);
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $slika = $result2['url'];
    echo "<img src='" . $slika . "' alt='profile picture of " . $_SESSION['username'] . "' width='100' height='100'>";
    
    echo "<br><br>";
    $sql3 = "SELECT opis FROM uporabniki WHERE id = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->execute([$_SESSION['id']]);
    $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
    $opis = $result3['opis'];

    echo $_SESSION['username'];
    echo "<br><br>";
    if($opis != null){
        echo $opis;
    }
    ?>
<button class='user-button' onclick="location.href='profile_edit.php'">Edit profile</button>
<br>
<?php
  echo "<br><br>";
  echo "<h2>Mnenja uporabnikov</h2>";
  if(userloggedIn()){
    echo "Dodaj komentar: <br>";
    echo "<form action='addkomentar.php?id=".$_SESSION['id']."' method='post'>";
    //dodaj dva boxa če je mnenje pozitivno ali negativno
    echo "<textarea name='mnenje' rows='5' cols='40'></textarea><br>";
    echo "<button class='download-button' type='submit'>Oddaj komentar</button>";
    echo "</form>";
    echo "<br>";
}

  //prikaži komentarje uporabnikov
  $sql = "SELECT * FROM komentarji WHERE profil_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$_SESSION['id']]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if($result != null){
      foreach($result as $row){
          echo "<div class='user'>";
          $user_id = $row['pisatelj_id'];
          $mnenje = $row['text'];
          $sql = "SELECT * FROM uporabniki WHERE id = ?";
          $stmt = $conn->prepare($sql);
          $stmt->execute([$user_id]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          $username = $result['username'];
          $slika_id = $result['slika_id'];
          $sql_slika = "SELECT * FROM slike WHERE id = ?";
          $stmt_slika = $conn->prepare($sql_slika);
          $stmt_slika->execute([$slika_id]);
          $result_slika = $stmt_slika->fetch(PDO::FETCH_ASSOC);
          $slika = $result_slika['url'];
          //gumb za zbris mnenja
          if(userloggedIn()){
              if($user_id === $_SESSION['id']){
                  echo "<button class='delete-mnenje-button' onclick=\"location.href='deletekomentar.php?id=".$row['id']."'\">Odstrani mnenje</button>";
              }
          }
          echo "<img src='" . $slika . "' alt='slika uporabnika' height='100px'>
          <p><b>" . $username . ": </b><br>";
          echo $mnenje . "</p>";
          echo "</div>";
      }
  }
  else{
      echo "<p>Nimaš še mnenj na profilu.</p>";
  }

?>
    
</div>

<?php
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
