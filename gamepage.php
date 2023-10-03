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
$_SESSION['lastlocation']="gamepage.php?id=".$_GET['id'];
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
    <?php
    require_once 'connect.php';

    $game_id = $_GET['id'];

    // Prepare the first SQL statement to retrieve slika_id
    $sql1 = "SELECT * FROM igre WHERE id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute([$game_id]);
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $ime = $result1['ime'];
    $opis = $result1['opis'];
    $cena = $result1['cena'];
    $zanr = $result1['zanr'];
    $user_id = $result1['uporabnik_id'];
    $url = $result1['file_url'];

    echo "<h1>".$ime."</h1><br><br>";
    echo "<p><b>Zanr: </b>".$zanr."</p>";
    echo "<p><b>Cena: </b>".$cena."€</p>";
    if($opis != null){
        echo "<p>".$opis."</p>";
    }
    else{
        echo "<p>Opis ni na voljo.</p>";
    }
    echo "<br><br>";


    $sql2 = "SELECT * FROM uporabniki WHERE id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute([$user_id]);
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $username = $result2['username'];

    $sql2 = "SELECT url FROM slike WHERE igra_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute([$game_id]);
    while($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        echo "<img src='".$result2['url']."' alt='slika igre' width='20%' >  ";
    }
    echo "<br><br>";

    echo "<p><b>Ustvarjalec: </b></p>";
    echo "<button class='profile-button' onclick=\"location.href='profiles.php?id=".$user_id."'\">".$username."</button><br><br>";
    if(IgraKupljena($conn, $game_id)){
        echo "<button class='download-button' onclick=\"location.href='".$url."'\">Prenesi igro</button>";
    }
    else{
        echo "<button class='download-button' onclick=\"location.href='buygame.php?id=" . $game_id . "'\">Kupi igro</button>";
    }

    echo "<br><br>";
    //Prikaži komentarje za igro, če jih ni, izpiši, da jih ni
$sql = "SELECT * FROM mnenja WHERE igra_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$game_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<h2>Mnenja: </h2>";

if(IgraKupljena($conn, $game_id)){
    echo "Dodaj mnenje: <br>";
    echo "<form action='addmnenje.php?id=".$game_id."' method='post'>";
    //dodaj dva boxa če je mnenje pozitivno ali negativno
    echo "<input type='radio' id='pozitivno' name='ocena' value='1' checked>";
    echo "<label for='pozitivno'>Pozitivno</label><br>";
    echo "<input type='radio' id='negativno' name='ocena' value='0'>";
    echo "<label for='negativno'>Negativno</label><br>";
    echo "<textarea name='mnenje' rows='5' cols='40'></textarea><br>";
    echo "<button class='download-button' type='submit'>Dodaj mnenje</button>";
    echo "</form>";
    echo "<br>";
}
if (empty($results)) {

    echo "<p>Ni mnenj.</p>";
} else {
    foreach ($results as $row) {
        echo "<div class='user'>";
        $mnenje = $row['text'];
        $user_id = $row['uporabnik_id'];

        // Pridobi uporabniško ime
        $sql_username = "SELECT * FROM uporabniki WHERE id = ?";
        $stmt_username = $conn->prepare($sql_username);
        $stmt_username->execute([$user_id]);
        $result_username = $stmt_username->fetch(PDO::FETCH_ASSOC);
        $username = $result_username['username'];
        $slika_id = $result_username['slika_id'];
        //Pridobi sliko uporabnika
        $sql_slika = "SELECT * FROM slike WHERE id = ?";
        $stmt_slika = $conn->prepare($sql_slika);
        $stmt_slika->execute([$slika_id]);
        $result_slika = $stmt_slika->fetch(PDO::FETCH_ASSOC);
        $slika = $result_slika['url'];
        //gumb za zbris mnenja
        if(userloggedIn()){
            if($user_id === $_SESSION['id']){
                echo "<button class='delete-mnenje-button' onclick=\"location.href='deletemnenje.php?id=".$row['id']."'\">Odstrani mnenje</button>";
            }
            else if(isUserAdmin($conn)){
                echo "<button class='delete-mnenje-button' onclick=\"location.href='deletemnenjeadmin.php?id=".$row['id']."'\">Odstrani mnenje</button>";
            }
        }
        
        echo "<img src='" . $slika . "' alt='slika uporabnika' height='100px'>
        <p><b>" . $username . ": </b><br>";
        echo $mnenje . "</p>";
        echo "</div>";
    }
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
include_once "alert.php";
?>
</body>
</html>
