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
$profile_id = $_GET['id'];
$text = $_POST['mnenje'];

    // Prepare the INSERT statement
    $sql = "INSERT INTO mnenja (text, profil_id, pisatelj_id)
    VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$text, $profile_id, $uporabnik])) { // Pass parameters as an array
        setcookie('prijava', "Komentar oddano!");
        setcookie('good', 1);
        header('Location: profiles.php?id='.$profile_id.'');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: index.php');
        exit();
    }
$conn = null; // Close the connection
?>
