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
        <b style="color:white; font-family:'Courier New', Courier, monospace">SteamCopy</b>
    </div>
    <div class="navbar-center">
        <?php
        session_start();
        require_once 'connect.php';
        require_once "alert.php";
        $isAdmin = isUserAdmin($conn);
        if (isBanned($conn)) {
            session_destroy();
            setcookie('prijava', 'Vaš račun je blokiran.');
            setcookie('error', 1);
            header("Location: index.php");
            die();
        }

        $storeButton = "<button class='center-button' onclick=\"location.href='index.php'\">Store</button>";
        $libraryButton = "<button class='center-button' onclick=\"location.href='library.php'\">Library</button>";
        $communityButton = "<button class='center-button' onclick=\"location.href='community.php'\">Community</button>";

        if ($isAdmin) {
            echo $storeButton;
            echo $libraryButton;
            echo $communityButton;
        } else {
            echo $storeButton;
            echo $libraryButton;
            echo $communityButton;
        }
        ?>
    </div>
    <div class="navbar-right">
        <?php
        if (!isset($_SESSION['id'])) {
            setcookie('prijava', "Za to stran se rabiš prijaviti.");
            setcookie('warning', 1);
            header('Location: prijava.php');
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

<br>
<div class="container">
    <h1>Game Upload</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="ime">Ime igre:</label>
        <input type="text" id="ime" name="ime" required><br><br>
        <label for="cena">Cena igre:</label>
        <input type="number" id="cena" name="cena" required step="0.01" min="0"><br><br>
        <label for="zanr">Žanr igre:</label>
        <select name="zanr" id="zanr">
            <?php
            $sql = "SELECT * FROM zanri";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ime = $row['ime'];
                $id = $row['id'];
                echo "<option value='$id'>$ime</option>";
            }
            ?>
        </select>
        <label for="opis">Opis igre:</label>
        <textarea id="opis" name="opis" rows="4" cols="50" required></textarea>
        <label for="zip">Datoteke igre:</label>
        <input type="file" id="zip" name="zip" required accept = ".zip, .rar"><br><br>
        <label for="email">Slike igre:</label>
        <input type="file" id="slika" name="slika[]" required multiple><br><br>
        <input type="submit" value="Pošlji">
    </form>
    <br>
    <button id='user-button' onclick="location.href='index.php'">Nazaj</button>
</div>
<div id="loginWindow">
    <?php
    function userLoggedIn()
    {
        return isset($_SESSION['username']);
    }

    function isUserAdmin($conn)
    {
        $sql = "SELECT * FROM uporabniki WHERE id = ? AND admin = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false;
    }

    function isBanned($conn)
    {
        if (!isset($_SESSION['id'])) return false;
        $sql = "SELECT * FROM uporabniki WHERE id = ? AND banned = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false;
    }
    ?>
</div>
</body>
</html>
