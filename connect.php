<?php
$servername = "safko.eu";
$username = "safkoeu_miha";
$password = "m1h42005";
$database = "safkoeu_steam";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
