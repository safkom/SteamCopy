<?php
require_once 'connect.php';
session_start();

// Validate and sanitize user input
$uporabnik = $_SESSION['id'];
$profil = $_GET['profile_id'];

    // Prepare the INSERT statement
    $sql = "DELETE FROM friends WHERE (requester_id = ? AND user_id = ?) OR (user_id = ? AND requester_id = ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$uporabnik, $profil, $uporabnik, $profil])) { // Pass parameters as an array
        setcookie('prijava', "Prošnja izbrisana!");
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
