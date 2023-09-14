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
if(!isset($_GET['id'])){
    header('Location: community.php');
    exit();
}
?>

<body> 
<nav class="navbar">
        <div class="navbar-left">
            <b style = "color:white; font-family:'Courier New', Courier, monospace">SteamCopy</b>
        </div>
        <div class="navbar-center">
            <button class="center-button" onclick="location.href='index.php'">Store</button>
            <button class="center-button" onclick="location.href='community.php'">Community</button>
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
        echo $opis;
    }

    



    if($profile_id != $_SESSION['id']){
        $sql1 = "SELECT * FROM friends WHERE (requester_id = ? AND user_id = ?) OR (user_id = ? AND requester_id = ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$_SESSION['id'], $profile_id, $_SESSION['id'], $profile_id]);

        // Check if there are any rows returned
        if ($stmt1->rowCount() > 0) {
            $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

            // Check the value of the 'prosnja_sprejeta' column
            $prosnja_sprejeta = $result1['prosnja_sprejeta'];

            // Display the appropriate button based on the 'prosnja_sprejeta' value
            if ($prosnja_sprejeta === 1) {
                echo "<button class = 'profile-button' onclick=\"location.href='cancelfriend.php?profile_id=".$profile_id."'\">Prekliči prijateljstvo</button>";
            }
           elseif ($prosnja_sprejeta === 0) {
            echo "<button class='profile-button' onclick=\"location.href='cancelfriend.php?profile_id=".$profile_id."'\">Prekliči zahtevo</button>";
           }
        }
        else {
            echo "<button class='profile-button' onclick=\"location.href='addfriend.php?profile_id=".$profile_id."'\">Dodaj prijatelja</button>";
        }

        
    }
    ?>
</div>






<?php

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
