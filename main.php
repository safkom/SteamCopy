<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko">
    <meta name="author" content="Miha Šafranko">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel ="stylesheet" type ="text/css"href="css/style.css">
    <title>Steam</title>
    <style>
        body{
            background: linear-gradient(90deg, #C7C5F4, #776BCC);
        }
        #container {
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px; /* Adding rounded corners */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Increasing the box shadow */
        }

        .dropdown {
        position: relative;
        display: inline-block;
    }

    .menu-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: blue;
        color: white;
        padding: 10px;
        font-size: 16px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }

    .menu-content {
        display: none;
        position: fixed;
        top: 60px;
        right: 20px;
        background-color: #f9f9f9;
        min-width: 120px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .menu-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    #gumbi {
        display: flex;
        justify-content: space-between;
    }
    .gumbstyle{
        background: #3498db;
  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
  background-image: -o-linear-gradient(top, #3498db, #2980b9);
  background-image: linear-gradient(to bottom, #3498db, #2980b9);
  -webkit-border-radius: 28;
  -moz-border-radius: 28;
  border-radius: 28px;
  font-family: Arial;
  color: #ffffff;
  font-size: 20px;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
    }
    .gumbstyle:hover {
        background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
    }
    #loginWindow {
    position: fixed;
    bottom: 10px;
    right: 10px;
    width: 200px;
    min-height: 30px;
    background-color: lightgreen;
    display: none;
    padding: 10px;
    border-radius: 5px;
}
    #errorWindow {
  position: fixed;
  bottom: 10px;
  right: 10px;
  width: 200px;
  min-height: 30px;
  background-color: lightcoral;
  display: none;
  padding: 10px;
  border-radius: 5px;
}
#warningWindow {
  position: fixed;
  bottom: 10px;
  right: 10px;
  width: 200px;
  min-height: 30px;
  background-color: lightblue;
  display: none;
  padding: 10px;
  border-radius: 5px;
}
.filter-button {
  padding: 10px 20px;
  background-color: #c0befc;
  color: white;
  border: none;
  cursor: pointer;
  font-size: 16px;
}

.filter-button:hover {
  background-color: #deddfd;
}

.filter-button:active {
  background-color: #deddfd;
}
    </style>    
</head>

<body onload="toggleFilterOptions()">
<div id="container">
<div class="dropdown">
    <button id="menuBtn" class="menu-btn">Menu</button>
    <div id="menuContent" class="menu-content">
        <a href="rezervacije.php">Rezervacije</a>
        <a href="odjava.php">Odjava</a>
    </div>
</div>

<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION["id"])) {
    header('Location: index.php');
    exit();
}

if (isset($_SESSION["admin"])) {
    header('Location: admin.php');
    exit();
}

$id = $_SESSION["id"];

$sql = "SELECT * FROM uporabniki WHERE id = ?;";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$query = mysqli_num_rows($result);

if ($query == 0) {
    header('Location: index.php');
    exit();
}



$sql = "SELECT * FROM zivali;";
$result = mysqli_query($conn, $sql);
?>
    <p>Seznam kužkov:</p>
    <button class="filter-button" onclick="toggleFilterOptions()">Filter</button>
    <br>

  <div id="filterOptionsContainer" class="filter-options">
    <label for="filterName">Filtriraj po imenu:</label>
    <input type="text" id="filterName" oninput="filterTable()">

    <label for="sortAge">Sortiraj po starosi:</label>
    <select id="sortAge" onchange="filterTable()">
      <option value="asc">Naraščajoče</option>
      <option value="desc">Padajoče</option>
    </select>
  </div>
  <br>
  <div class="container">
			<div class="row justify-content-center">
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-wrap">
						<table class="table">
						  <thead class="thead-dark">
						    <tr>
						      <th>Ime</th>
						      <th>Starost</th>
						      <th>Posvojen</th>
						      <th>Slika</th>
							  <th>Rezerviraj za ogled</th>
						    </tr>
                </thread>
        <?php
        while ($row = mysqli_fetch_array($result)) {
            $slikaid = $row['slika_id'];
            $sql1 = "SELECT * FROM slike WHERE id = '$slikaid';";
            $klic = mysqli_query($conn, $sql1);
            $klic1 = mysqli_fetch_array($klic);

            if ($klic1 !== null) {
                $slika = $klic1['url'];
            } else {
                $slika = null;
            }

            $dateOfBirth = $row['datum_r'];
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $ageInMonths = $diff->format('%m');
            $ageInYears = $diff->format('%y');

            if ($ageInYears == 1) {
                $leta = $ageInYears . ' leto in ';
            } elseif ($ageInYears > 1 && $ageInYears < 5) {
                $leta = $ageInYears . ' leti in ';
            } elseif ($ageInYears >= 5) {
                $leta = $ageInYears . ' let in ';
            } else {
                $leta = '';
            }

            if ($ageInMonths == 1) {
                $age = $leta . '1 mesec';
            } elseif ($ageInMonths > 1 && $ageInMonths < 5) {
                $age = $leta . $ageInMonths . ' meseci';
            } elseif ($ageInMonths >= 5) {
                $age = $leta . $ageInMonths . ' mesecev';
            } elseif ($ageInMonths == 0 && $ageInYears == 0) {
                $age = 'Manj kot 1 mesec.';
            } else {
                $age = '';
            }

            if ($row['posvojen'] == 0) {
                $posvojen = 'Ne';
            } else {
                $posvojen = 'Da';
            }

            if (is_null($row['rezervacija_id'])) {
                $rezervacija = "<a href='rezerviraj.php?zival_id=".$row['id']."'>Rezerviraj</a>";
            } else {
                $rezervacija = 'Rezervirano';
            }
            echo '<tr class = "alert" role = "alert">';
            echo '<td>'.$row['ime']."</td><td>".$age."</td><td>".$posvojen."</td><td>";

            if (!empty($slika)) {
                echo "<img src='".$slika."'>";
            } else {
                echo "Ni slike";
            }

            echo "</td><td>".$rezervacija."</td>";
            echo '</tr>';
        }
        ?>
    </table>
    <br>
    <div id="gumbi">
    <button class="gumbstyle" onclick="location.href = 'sponzorstva.php';">Sponzorstva</button>
    <button class="gumbstyle" onclick="location.href = 'rezervacije.php';">Rezervacije</button>
    </div>  
</div>


    <?php
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
var filterOptionsContainer = document.getElementById("filterOptionsContainer");
var filterNameInput = document.getElementById("filterName");
var sortAgeSelect = document.getElementById("sortAge");

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
  sortAgeSelect.disabled = false;
}

function disableFilterInputs() {
  filterNameInput.disabled = true;
  sortAgeSelect.disabled = true;
  filterNameInput.value = ""; // Clear filter name input
  sortAgeSelect.value = "default"; // Reset sort age select
}

function filterTable() {
  var filterNameValue = filterNameInput.value.toLowerCase();
  var sortAgeValue = sortAgeSelect.value;

  var rows = document.querySelectorAll("table tr");

  for (var i = 1; i < rows.length; i++) {
    var name = rows[i].getElementsByTagName("td")[0].textContent.toLowerCase();

    if (filterOptionsContainer.style.display === "none") {
      rows[i].style.display = ""; // Display all rows when filter options are hidden
    } else if (name.includes(filterNameValue)) {
      rows[i].style.display = "";
    } else {
      rows[i].style.display = "none";
    }
  }

  if (sortAgeValue === "asc") {
    var sortedRows = Array.from(rows).slice(1).sort(function(a, b) {
      var ageA = parseInt(a.getElementsByTagName("td")[1].textContent);
      var ageB = parseInt(b.getElementsByTagName("td")[1].textContent);
      return ageA - ageB;
    });
  } else if (sortAgeValue === "desc") {
    var sortedRows = Array.from(rows).slice(1).sort(function(a, b) {
      var ageA = parseInt(a.getElementsByTagName("td")[1].textContent);
      var ageB = parseInt(b.getElementsByTagName("td")[1].textContent);
      return ageB - ageA;
    });
  } else {
    var sortedRows = Array.from(rows).slice(1);
  }

  var table = document.querySelector("table");
  table.innerHTML = "";
  table.appendChild(rows[0]);

  sortedRows.forEach(function(row) {
    table.appendChild(row);
  });
}

document.getElementById("filterName").addEventListener("input", filterTable);
document.getElementById("sortAge").addEventListener("change", filterTable);


var menuBtn = document.getElementById("menuBtn");
    var menuContent = document.getElementById("menuContent");

    menuBtn.addEventListener("click", function() {
        menuContent.style.display = (menuContent.style.display === "block") ? "none" : "block";
    });

    window.addEventListener("click", function(event) {
        if (!event.target.matches("#menuBtn")) {
            if (menuContent.style.display === "block") {
                menuContent.style.display = "none";
            }
        }
    });
    // Hide all the div elements initially
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
