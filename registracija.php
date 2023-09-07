<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Miha Šafranko"/>
	<meta name="author" content="Miha Šafranko" />
    <link rel="stylesheet" href="css/login.css">
    <style>
        body{
            background: linear-gradient(90deg, #C7C5F4, #776BCC);
        }
        /* Global Styles */
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(90deg, #C7C5F4, #776BCC);	
    margin: 0;
    padding: 0;
  }
  
  .container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  background-color: #fff;
  border-radius: 10px; /* Adding rounded corners */
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Increasing the box shadow */
}


  
  h1 {
    text-align: center;
    color: #333;
  }
  
  form {
    margin-top: 20px;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
    color: #555;
  }
  
  .container input[type="text"],
.container input[type="password"] {
  width: 100%;
  padding: 10px;
	font: inherit;
	box-shadow: 0 6px 10px 0 rgba(0, 0, 0 , .1);
	border-color: gray;
	outline: 0;
}

.container input[type="submit"] {
  width: 100%;
  padding: 10px;
  background-color: lightblue;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.container input[type="submit"]:hover {
  background-color: lightblue;
}
  
  a {
    color: lightblue;
    text-decoration: none;
  }
  
  /* Responsive Styles */
  @media (max-width: 600px) {
    .container {
      max-width: 100%;
      margin: 20px;
      box-shadow: none;
    }
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
  
    </style>

    <title>Zavetišče</title>
</head>
<body>
  <?php
  session_start();
  ?>
  <div class = "container">
    <h1>Registracija</h1>
 <form action="register.php" method="get">
  <label for="ime">Ime:</label>
  <input type="text" id="ime" name="ime" required><br><br>
  <label for="priimek">Priimek:</label>
  <input type="text" id="priimek" name="priimek" required><br><br>
  <label for="email">Mail:</label>
  <input type="text" id="email" name="email" required><br><br>
  <label for="geslo">Geslo:</label>
  <input type="password" id="geslo" name="geslo" required><br><br>
  <label for="naslov">Naslov:</label>
  <input type="text" id="naslov" name="naslov" required><br><br>
  <label for="kraji">Kraj:</label>
  <input type="text" id="kraj" name = "kraj" list="kraji" required><br><br>
  <datalist id="kraji">
  <?php
require_once 'connect.php';
$sql = "SELECT kraj FROM kraji";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
  echo '<option value="' . $row['kraj'] . '">';
}
?>

</datalist>
  <input type="submit" value="Pošlji">
</form>
<p>Ste že uporabnik? <a href = "index.php">Pojdite na prijavo</a>
  </div>
  <div id="loginWindow">
    <?php
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