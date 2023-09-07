<?php
$servername = "safko.eu";
$username = "safkoeu_miha";
$password = "m1h42005";
$database = "safkoeu_steam";

// Create connection

$conn = mysqli_connect($servername, $username, $password, $database);


// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8")

?>