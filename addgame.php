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
          if(isUserAdmin()){
          }
          else{
            echo "<button class='center-button' onclick=\"location.href='index.php'\">Store</button>";
            echo "<button class='center-button' onclick=\"location.href='admin_library.php'\">Library</button>";
            echo "<button class='center-button' onclick=\"location.href='community_admin.php'\">Community</button>";
          }
            

            ?>
        </div>
        <div class="navbar-right">
            <?php
            session_start();
            if (!isset($_SESSION['id'])) {
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

function isUserAdmin(){
  $sql = "SELECT * FROM uporabniki WHERE id = ? AND admin = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$_SESSION['id']]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($result == false) {
      return false;
  }
  else{
    return true;
  }
}
    if (isset($_COOKIE['prijava'])) {
        echo "✅ ";
        echo $_COOKIE['prijava'];
        // setcookie("prijava", "", time() - 3600);
    }
    ?>
</div>
<div id="errorWindow">
    <?php
    if (isset($_COOKIE['prijava'])) {
        echo "⛔ ";
        echo $_COOKIE['prijava'];
        // setcookie("prijava", "", time() - 3600);
    }
    ?>
</div>
<div id="warningWindow">
    <?php
    if (isset($_COOKIE['prijava'])) {
        echo "⚠️ ";
        echo $_COOKIE['prijava'];
        // setcookie("prijava", "", time() - 3600);
    }
    ?>
</div>

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
</body>
</html>