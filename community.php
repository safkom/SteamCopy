<!DOCTYPE html>
<html lang="sl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko">
    <meta name="author" content="Miha Šafranko">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <style>
       
</style>
    <title>SteamCopy</title>
</head>

<?php
session_start();
require_once 'connect.php';
$isAdmin = isUserAdmin($conn);
$_SESSION['lastlocation']="community.php";

function userLoggedIn()
{
    if (isset($_SESSION['username'])) {
        return true;
    } else {
        return false;
    }
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
            <b style="color:white; font-family:'Courier New', Courier, monospace">SteamCopy</b>
        </div>
        <div class="navbar-center">
        <?php
          if($isAdmin){
            echo "<button class='center-button' onclick=\"location.href='index.php'\">Store</button>";
            echo "<button class='center-button' onclick=\"location.href='admin_library.php'\">Library</button>";
            echo "<button class='selected-button' onclick=\"location.href='community_admin.php'\">Community</button>";
          }
          else{
            echo "<button class='center-button' onclick=\"location.href='index.php'\">Store</button>";
            echo "<button class='center-button' onclick=\"location.href='library.php'\">Library</button>";
            echo "<button class='selected-button' onclick=\"location.href='community.php'\">Community</button>";
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
    <div class='content-below-navbar'>
    <br>
    <div id="container">
        <h1>Community</h1>
        <div id="filterOptionsContainer" class="filter-options">
        <label for="filterName">Išči uporabnike:</label>
        <input type="text" id="filterName" oninput="filterTable()">
        <br>
        <p>Uporabniki:</p>
        <?php

        if (!isset($_POST['isci'])) {
            $sql = "SELECT * FROM uporabniki";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                echo "<button class='profile-button' onclick=\"location.href='profiles.php?id=".$user_id."'\">Poglej</button>";
                echo "</div>";
                echo "</div>";
            }
        }
        if (isset($_POST['isci'])) { //check if form was submitted
            $search_term = "%" . $_POST['iskanje'] . "%"; // Add wildcards for SQL LIKE
            $sql = "SELECT * FROM uporabniki WHERE username LIKE :search_term";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":search_term", $search_term, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                echo "<img src='$url' width='100' height='100' alt='Avatar' class='avatar'>";
                echo "<div class='user-info'>";
                echo "<p><b>$username</b></p>";
                echo "<p>$opis</p>";
                echo "<button class='profile-button' onclick=\"location.href='profiles.php?id=".$user_id."'\">Poglej</button>";
                echo "</div>";
                echo "</div>";
            }
        }

        ?>
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
      </div>
      <script>
        var filterOptionsContainer = document.getElementById("filterOptionsContainer");
        var filterNameInput = document.getElementById("filterName");
        
        function toggleFilterOptions() {
            if (filterOptionsContainer.style.display === "none") {
                filterOptionsContainer.style.display = "block";
                enableFilterInputs();
            } else {
                filterOptionsContainer.style.display = "none";
                disableFilterInputs();
                filterUsers(); // Filter the users when hiding the filter options
            }
        }

        function enableFilterInputs() {
            filterNameInput.disabled = false;
        }

        function disableFilterInputs() {
            filterNameInput.disabled = true;
            filterNameInput.value = "";
        }

        function filterUsers() {
            var filterNameValue = filterNameInput.value.toLowerCase();
            var userDivs = document.querySelectorAll(".user-info");

            for (var i = 0; i < userDivs.length; i++) {
                var username = userDivs[i].getElementsByTagName("p")[0].textContent.toLowerCase();
                var parentDiv = userDivs[i].parentNode;
                var showUser = true;

                if (filterNameValue !== '' && !username.includes(filterNameValue)) {
                    showUser = false;
                }

                parentDiv.style.display = showUser ? "block" : "none";
            }
        }

        document.getElementById("filterName").addEventListener("input", filterUsers);

        // Initial filter users
        filterUsers();
    </script>
</body>

</html>
