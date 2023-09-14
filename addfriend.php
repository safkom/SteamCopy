<?php
require_once 'connect.php';
session_start();

// Validate and sanitize user input
$uporabnik = $_SESSION['id'];
$profil = $_GET['profile_id'];

    // Prepare the INSERT statement
    $sql = "INSERT INTO friends (requester_id, user_id)
    VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$uporabnik, $profil])) { // Pass parameters as an array
        setcookie('prijava', "Prošnja za prijateljstvo poslana!");
        setcookie('good', 1);
        header('Location: profiles.php?id='.$profil.'');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: profiles.php');
        exit();
    }
$conn = null; // Close the connection
?>
