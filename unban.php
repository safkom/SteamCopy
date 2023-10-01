<?php
require_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: '. $_SESSION['lastlocation'] .'');
    exit();
}
$isAdmin = isUserAdmin($conn);
if (!$isAdmin) {
    setcookie('prijava', "Tu nimaš dostopa.");
    setcookie('error', 1);
    header('Location: '. $_SESSION['lastlocation'] .'');
    exit();
}

$sql = "SELECT * FROM uporabniki WHERE id = ? AND admin = 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result == false) {
    header('Location: '. $_SESSION['lastlocation'] .'');
    exit();
}

// Validate and sanitize user input
$profil = $_GET['id'];

    // Prepare the INSERT statement
    $sql = "UPDATE uporabniki SET banned = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$profil])) { // Pass parameters as an array
        setcookie('prijava', "Uporabnik unbanned!");
        setcookie('good', 1);
        header('Location: '. $_SESSION['lastlocation'] .'');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: '. $_SESSION['lastlocation'] .'');
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
