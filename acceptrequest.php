<?php
require_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Tu nimaš dostopa.");
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}

// Validate and sanitize user input
$uporabnik = $_SESSION['id'];
$profil = $_GET['profile_id'];

    // Prepare the INSERT statement
    $sql = "UPDATE friends SET prosnja_sprejeta = 1 WHERE user_id = ? AND requester_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$uporabnik, $profil])) { // Pass parameters as an array
        setcookie('prijava', "Prošnja sprejeta!");
        setcookie('good', 1);
        header('Location: friends.php');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: friends.php');
        exit();
    }
    
   
$conn = null; // Close the connection
?>
