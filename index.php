<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko">
    <meta name="author" content="Miha Šafranko">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <title>SteamCopy</title>  
</head>
<?php
session_start();
require_once "connect.php";
$isAdmin = isUserAdmin($conn);
$_SESSION['lastlocation']="index.php";

// Check if user is banned
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
            echo "<button class='selected-button' onclick=\"location.href='index.php'\">Store</button>";
            echo "<button class='center-button' onclick=\"location.href='admin_library.php'\">Library</button>";
            echo "<button class='center-button' onclick=\"location.href='community_admin.php'\">Community</button>";
          }
          else{
            echo "<button class='selected-button' onclick=\"location.href='index.php'\">Store</button>";
            echo "<button class='center-button' onclick=\"location.href='library.php'\">Library</button>";
            echo "<button class='center-button' onclick=\"location.href='community.php'\">Community</button>";
          }
            ?>
        </div>
        <div class="navbar-right">
            <?php
            if (userLoggedIn()) {
              echo "<button class='user-button'>Stanje: ".Stanje($conn)."€</button>";
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
    <h1 style="text-align: center;">Store</h1>
    <?php
      if(isset($_SESSION['id'])){
        echo "<button class='store-button' onclick=\"location.href='addgame.php'\">Dodaj igro</button>";
      }
    ?>
    <p>Išči igre:</p>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <input type="text" name="iskanje" placeholder="Vnesi ime igre">
            <select name="zanr" id="zanr">
    <option value="">Vsi žanri</option>
    <option value="FPS">FPS</option>
    <option value="Platformer">Platformer</option>
    <option value="Strategy">Strategy</option>
    <option value="Art">ART</option>
    <option value="RPG">RPG</option>
<option value="Adventure">Adventure</option>
<option value="Simulation">Simulation</option>
<option value="Sports">Sports</option>
<option value="Puzzle">Puzzle</option>
<option value="Racing">Racing</option>
<option value="Fighting">Fighting</option>
<option value="Horror">Horror</option>
<option value="Survival">Survival</option>
<option value="Music">Music</option>
<option value="Casual">Casual</option>
<option value="Educational">Educational</option>
<option value="Party">Party</option>
<option value="MOBA">MOBA</option>
<option value="Card">Card</option>
<option value="Board">Board</option>
<option value="Shooter">Shooter</option>
  </select>
            <label for="min_price">Min Cena:</label>
            <input type="number" id="min_price" name="min_price" min="0" step="0.01">
            <label for="max_price">Max Cena:</label>
            <input type="number" id="max_price" name="max_price" min="0" step="0.01">
            <label for="hide_owned">Skrij že kupljene igre:</label>
            <input type="checkbox" id="hide_owned" name="hide_owned" value="1">
            <input type="submit" name="isci" value="Išči">
            <input type="submit" name="reset" value="Ponastavi">
        </form>
        <br>
        <br>
        <br>
        <br>
        <?php
        if (!isset($_POST['isci'])) {
            $sql = "SELECT * FROM igre";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $game_id = $row['id'];
                $ime = $row['ime'];
                $opis = $row['opis'];
                $cena = $row['cena'];
                $zanr = $row['zanr'];
                $user_id = $row['uporabnik_id'];
                $file = $row['file_url'];
                echo "<div class='user'>";
                echo "<div class='user-info'>";
                echo "<p><b>$ime</b></p><br>";
                echo "<p>Cena: <b>".$cena."€</b></p><br>";
                echo "<p>Opis: $opis</p><br>";
                echo "<p>Žanr: $zanr</p><br>";
                $sql2 = "SELECT * FROM slike WHERE igra_id = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$row['id']]);
                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    echo "<img src='" . $row2['url'] . "' alt='slika igre' width='20%' >  ";
                }
                echo "<br>";
                echo "<br>";
                echo "<button class='profile-button' onclick=\"location.href='gamepage.php?id=" . $game_id . "'\">Poglej</button><br><br>";
                if(!IgraKupljena($conn, $game_id)){
                  echo "<button class='download-button' onclick=\"location.href='deletegameuser.php?id=" . $game_id . "'\">Kupi igro</button>";
                }
                echo "</div>";
                echo "</div>";
            }
            }
        if (isset($_POST['isci'])) {
            $search_term = "%" . strtolower($_POST['iskanje']) . "%"; // Convert to lowercase and add wildcards for SQL LIKE
            $zanr_filter = $_POST['zanr'];
            $min_price = isset($_POST['min_price']) ? $_POST['min_price'] : 0;
            $max_price = isset($_POST['max_price']) ? $_POST['max_price'] : PHP_INT_MAX;
            $hide_owned = isset($_POST['hide_owned']) ? true : false;
        
            $sql = "SELECT * FROM igre WHERE ime LIKE :search_term AND cena >= :min_price AND cena <= :max_price";
            if (!empty($zanr_filter)) {
                $sql .= " AND zanr = :zanr_filter";
            }
            echo "SQL: $sql<br>";
            var_dump($search_term, $min_price, $max_price, $zanr_filter);

        
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":search_term", $search_term, PDO::PARAM_STR);
            $stmt->bindParam(":min_price", $min_price, PDO::PARAM_INT);
            $stmt->bindParam(":max_price", $max_price, PDO::PARAM_INT);
            if (!empty($zanr_filter)) {
                $stmt->bindParam(":zanr_filter", $zanr_filter, PDO::PARAM_STR);
            }
            $stmt->execute();

            // Fetch and display the filtered games
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $game_id = $row['id'];
              $ime = $row['ime'];
              $opis = $row['opis'];
              $cena = $row['cena'];
              $zanr = $row['zanr'];
              $user_id = $row['uporabnik_id'];
              $file = $row['file_url'];
              if($hide_owned && IgraKupljena($conn, $game_id)) continue; // Skip this game if it's already owned by the user
              echo "<div class='user'>";
              echo "<div class='user-info'>";
              echo "<p><b>$ime</b></p><br>";
              echo "<p>Cena: <b>".$cena."€</b></p><br>";
              echo "<p>$opis</p><br>";
              echo "<p>$zanr</p><br>";
              $sql2 = "SELECT * FROM slike WHERE igra_id = ?";
              $stmt2 = $conn->prepare($sql2);
              $stmt2->execute([$row['id']]);
              while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                  echo "<img src='" . $row2['url'] . "' alt='slika igre' width='20%' >  ";
              }
              echo "<br>";
              echo "<br>";
              echo "<button class='profile-button' onclick=\"location.href='gamepage.php?id=" . $game_id . "'\">Poglej</button><br><br>";
              if(!IgraKupljena($conn, $game_id)){
                echo "<button class='download-button' onclick=\"location.href='deletegameuser.php?id=" . $game_id . "'\">Kupi igro</button>";
              }
              echo "</div>";
              echo "</div>";
            }
        }
        ?>
    </div>
    
    <?php
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
        if(!isset($_SESSION['id'])) return false;
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

    function Stanje($conn){
        $sql = "SELECT * FROM uporabniki WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['denar'];
    }

    function IgraKupljena($conn, $igra_id){
        if(!isset($_SESSION['id'])) return false;
        $sql = "SELECT * FROM nakupi WHERE uporabnik_id = ? AND igra_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['id'], $igra_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row == false){
            return false;
        }
        else{
            return true;
        }
    }

    function userLoggedIn(){
        if(isset($_SESSION['username'])){
            return true;
        }
        else{
            return false;
        }
    }
    include_once "alert.php";
    ?>
</body>
</html>
