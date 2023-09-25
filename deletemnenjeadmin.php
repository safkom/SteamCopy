<?php
require_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Za uporabo te strani, se rabiš prijaviti.");
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}
//preveri če je uporabnik admin
$isAdmin = isUserAdmin($conn);
if(!$isAdmin){
  setcookie('prijava', "Za uporabo te strani, nimaš pravic.");
  setcookie('error', 1);
  header('Location: index.php');
  exit();
}

// Validate and sanitize user input
$mnenje_id = $_GET['id'];

    // Prepare the INSERT statement
    $sql = "DELETE FROM mnenja WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$mnenje_id])) { // Pass parameters as an array
        setcookie('prijava', "Mnenje izbrisano!");
        setcookie('good', 1);
        header('Location: index.php?id=');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: index.php');
        exit();
    }

    function isUserAdmin($conn) {
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
$conn = null; // Close the connection
?>
