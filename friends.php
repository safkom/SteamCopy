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
    header('Location: index.php');
    exit();
}
?>

<body> 
<nav class="navbar">
        <div class="navbar-left">
            <b style = "color:white; font-family:'Courier New', Courier, monospace">SteamCopy</b>
        </div>
        <div class="navbar-center">
        <?php
          if(isUserAdmin()){
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
        <h1>Friend List</h1>
    <?php
    require_once 'connect.php';
    $sql = "SELECT * FROM friends WHERE user_id = ? AND prosnja_sprejeta = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $sql = "SELECT * FROM uporabniki WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$row['requester_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        $id = $row['id'];
        $username = $row['username'];
        $opis = $row['opis'];
        $slika_id = $row['slika_id'];
        $user_id = $row['id'];
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
        echo "<button class='profile-button' onclick=\"location.href='profiles.php?id=".$user_id."'\">Oglej profil</button>";
        echo "<button class='profile-button' onclick=\"location.href='deletefriend.php?id=".$user_id."'\">Odstrani prijatelja</button>";
        echo "</div>";
        echo "</div>";
    }
    ?>
    
</div>
<div id="container">
    <h1>Prejete Prošnje</h1>
    <?php 
    $sql = "SELECT * FROM friends WHERE user_id = ? AND prosnja_sprejeta = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    // Display rows from the first query
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
    echo "<button class='profile-button' onclick=\"location.href='acceptrequest.php?profile_id=".$user_id."'\">Sprejmi prošnjo</button>";
    echo "<button class='profile-button' onclick=\"location.href='cancelrequest.php?profile_id=".$user_id."'\">Zbriši prošnjo</button>";
    echo "</div>";
    echo "</div>";
}

// Display rows from the second query
?>

</div>

<div id="container">
<h1>Poslane Prošnje</h1>
    <?php 
    $sql = "SELECT * FROM friends WHERE requester_id = ? AND prosnja_sprejeta = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
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
}

// Display rows from the second query
?>





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
