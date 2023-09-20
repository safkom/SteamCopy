<?php
require_once 'connect.php';
session_start();
$isAdmin = isUserAdmin($conn);
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}
if(!$isAdmin) {
    setcookie('prijava', "Tu nimaš dostopa.");
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}

$sql = "SELECT * FROM uporabniki WHERE id = ? AND admin = 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result == false) {
    header('Location: index.php');
    exit();
}

// Validate and sanitize user input
$profil = $_GET['id'];

    // Prepare the INSERT statement
    $sql = "UPDATE uporabniki SET banned = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$profil])) { // Pass parameters as an array
        setcookie('prijava', "Uporabnik je banned!");
        setcookie('good', 1);
        header('Location: community_admin.php');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: community_admin.php');
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
