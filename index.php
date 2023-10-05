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
$_SESSION['lastlocation'] = "index.php";

// Check if user is banned
if (isBanned($conn)) {
    session_destroy();
    setcookie('prijava', 'Vaš račun je blokiran.');
    setcookie('error', 1);
    header("Location: index.php");
    die();
}
?>

<body onload="toggleFilterOptions()">
<nav class="navbar">
    <div class="navbar-left">
        <b style="color:white; font-family:'Courier New', Courier, monospace">SteamCopy</b>
    </div>
    <div class="navbar-center">
        <?php
        if ($isAdmin) {
            echo "<button class='selected-button' onclick=\"location.href='index.php'\">Store</button>";
            echo "<button class='center-button' onclick=\"location.href='admin_library.php'\">Library</button>";
            echo "<button class='center-button' onclick=\"location.href='community_admin.php'\">Community</button>";
        } else {
            echo "<button class='selected-button' onclick=\"location.href='index.php'\">Store</button>";
            echo "<button class='center-button' onclick=\"location.href='library.php'\">Library</button>";
            echo "<button class='center-button' onclick=\"location.href='community.php'\">Community</button>";
        }
        ?>
    </div>
    <div class="navbar-right">
        <?php
        if (userLoggedIn()) {
            $sql = "SELECT * FROM uporabniki WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_SESSION['id']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $denar = $row['denar'];
            echo "<button class='user-button'>Balance: $denar €</button>";
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
        if (isset($_SESSION['id'])) {
            echo "<button class='store-button' onclick=\"location.href='addgame.php'\">Dodaj igro</button>";
        }
        ?>
        <button class="filter-button" onclick="toggleFilterOptions()">Filter</button>
    <br>

  <div id="filterOptionsContainer" class="filter-options">
    <label for="filterName">Filtriraj po imenu:</label>
    <input type="text" id="filterName" oninput="filterTable()">
    <br>
    <br>
    <label for="priceRange">Cena:</label>
    <input type="range" id="priceRange" min="0" max="100" step="1" oninput="filterTable()">
    <div id="priceValues">Max: <span id="maxPrice">1000</span>€</div>
    <br><br>
    <label for="filterGenre">Filtriraj po žanru:</label>
    <select id="filterGenre" onchange="filterTable()">
        <?php
        echo "<option value=''>Vsi žanri</option>";
        $sql = "SELECT * FROM zanri";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ime = $row['ime'];
            echo "<option value='$ime'>$ime</option>";
        }
        ?>
    </select>
    <br><br>
    <?php
    if(isset($_SESSION['id'])){
        echo "<label for='filterOwnership'>Filtriraj po lastništvu:</label>
        <select id='filterOwnership' onchange='filterTable()'>
            <option value=''>Vse igre</option>
            <option value='owned'>Imam kupljeno</option>
            <option value='not-owned'>Nimam kupljeno</option>
        </select>";
    } else {
        echo "<label for='filterOwnership' style='display:none;'>Filtriraj po lastništvu:</label>
        <select id='filterOwnership' onchange='filterTable()' style='display:none;'>
            <option value=''>Vse igre</option>
            <option value='owned'>Imam kupljeno</option>
            <option value='not-owned'>Nimam kupljeno</option>
        </select>";
    }
    ?>
  </div>
  <br>
        <br>
        <br>
        <?php
        if(isset($_SESSION['id'])){
          $sql = "SELECT igre.*, nakupi.uporabnik_id AS nakup_uporabnik_id
                  FROM igre
                  LEFT JOIN nakupi ON igre.id = nakupi.igra_id AND nakupi.uporabnik_id = ?";
                  $stmt = $conn->prepare($sql);
                  $stmt->execute([$_SESSION['id']]);
        }else{
            $sql = "SELECT igre.*, nakupi.uporabnik_id AS nakup_uporabnik_id
                    FROM igre
                    LEFT JOIN nakupi ON igre.id = nakupi.igra_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
            }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $game_id = $row['id'];
        $ime = $row['ime'];
        $opis = $row['opis'];
        $zanr_id = $row['zanr_id'];
        $user_id = $row['uporabnik_id'];
        $file = $row['file_url'];
        $cena = $row['cena'];
        if(userLoggedIn()){
        $owned = ($row['nakup_uporabnik_id'] == $_SESSION['id']);
        }
        else{
            $owned = false;
        }

        $sql2 = "SELECT * FROM zanri WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$zanr_id]);
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $zanr = $row2['ime'];

        echo "<div class='user'>";
        echo "<div class='user-info'>";
        echo "<p><b>$ime</b></p><br>";
        echo "<p>Cena: $cena €</p><br>";
        echo "<p>$zanr</p><br>";
        $sql2 = "SELECT * FROM slike WHERE igra_id = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$row['id']]);
                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    echo "<img src='" . $row2['url'] . "' alt='slika igre' width='20%' >  ";
                }
        echo "<br>";
        if ($owned && userLoggedIn()) {
            echo "<button class='download-button' onclick=\"window.open('".$file."')\">Prenesi igro</button>";
        } else {
            echo "<button class='download-button' onclick=\"location.href='buygame.php?id=" . $game_id . "'\">Kupi igro</button>";
        }
        echo "<br>";
        echo "<br>";
        echo "<button class='profile-button' onclick=\"location.href='gamepage.php?id=" . $game_id . "'\">Poglej</button><br><br>";
        echo "</div>";
        echo "</div>";
    }
        ?>
    </div>

    <?php
    function isUserAdmin($conn)
    {
        if (!isset($_SESSION['id'])) return false; // Check if 'id' exists in the $_SESSION array
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
    
    function isBanned($conn)
    {
        if (!isset($_SESSION['id'])) return false; // Check if 'id' exists in the $_SESSION array
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

    function userLoggedIn()
    {
        if (isset($_SESSION['username'])) {
            return true;
        } else {
            return false;
        }
    }

    include_once "alert.php";
    ?>

<script>
var filterOptionsContainer = document.getElementById("filterOptionsContainer");
var filterNameInput = document.getElementById("filterName");
var priceRangeInput = document.getElementById("priceRange");
var maxPriceOutput = document.getElementById("maxPrice");
var filterGenreInput = document.getElementById("filterGenre");
var filterOwnershipInput = document.getElementById("filterOwnership");

function toggleFilterOptions() {
    if (filterOptionsContainer.style.display === "none") {
        filterOptionsContainer.style.display = "block";
        enableFilterInputs();
    } else {
        filterOptionsContainer.style.display = "none";
        disableFilterInputs();
        filterTable(); // Filter the table when hiding the filter options
    }
}

function enableFilterInputs() {
    filterNameInput.disabled = false;
    priceRangeInput.disabled = false;
    filterGenreInput.disabled = false;
    filterOwnershipInput.disabled = false;
}

function disableFilterInputs() {
    filterNameInput.disabled = true;
    priceRangeInput.disabled = true;
    filterGenreInput.disabled = true;
    filterOwnershipInput.disabled = true;

    filterNameInput.value = "";
    priceRangeInput.value = priceRangeInput.max;
    maxPriceOutput.textContent = priceRangeInput.max;
    filterGenreInput.value = "";
    filterOwnershipInput.value = "";
}

function filterTable() {
    var filterNameValue = filterNameInput.value.toLowerCase();
    var maxPriceValue = parseFloat(priceRangeInput.value);
    var filterGenreValue = filterGenreInput.value.toLowerCase();
    var filterOwnershipValue = filterOwnershipInput.value;

    var rows = document.querySelectorAll(".user-info");

    for (var i = 0; i < rows.length; i++) {
        var name = rows[i].getElementsByTagName("p")[0].textContent.toLowerCase();
        var price = parseFloat(rows[i].getElementsByTagName("p")[1].textContent.split(":")[1].trim());
        var genre = rows[i].getElementsByTagName("p")[2].textContent.toLowerCase();
        var ownershipText = rows[i].querySelector(".download-button").textContent.toLowerCase();

        // Check if the user is logged in or not
        var isUserLoggedIn = <?php echo userLoggedIn() ? 'true' : 'false'; ?>;
        var ownership = isUserLoggedIn ? ownershipText : "not-logged-in";

        var parentDiv = rows[i].parentNode;
        var showRow = true;

        if (filterNameValue !== '' && !name.includes(filterNameValue)) {
            showRow = false;
        }

        if (price > maxPriceValue) {
            showRow = false;
        }

        if (filterGenreValue !== '' && genre !== filterGenreValue) {
            showRow = false;
        }

        if (filterOwnershipValue === 'owned' && ownership !== 'prenesi igro') {
            showRow = false;
        }

        if (filterOwnershipValue === 'not-owned' && ownership === 'prenesi igro') {
            showRow = false;
        }

        // Handle the case where the user is not logged in
        if (!isUserLoggedIn && filterOwnershipValue === 'owned') {
            showRow = false;
        }

        parentDiv.style.display = showRow ? "block" : "none";
    }
}

document.getElementById("filterName").addEventListener("input", filterTable);
priceRangeInput.addEventListener("input", function() {
    maxPriceOutput.textContent = priceRangeInput.value;
    filterTable();
});
filterGenreInput.addEventListener("change", filterTable);
filterOwnershipInput.addEventListener("change", filterTable);

// Initial filter table
filterTable();
</script>

</div>
</body>
</html>
