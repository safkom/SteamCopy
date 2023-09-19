<?php
require_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
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
$profil = $_GET['profile_id'];

    // Prepare the INSERT statement
    $sql = "UPDATE uporabniki SET banned = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$uporabnik, $profil])) { // Pass parameters as an array
        setcookie('prijava', "Uporabnik unbanned!");
        setcookie('good', 1);
        header('Location: community_admin.php');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: community_admin.php');
        exit();
    }
    
   
$conn = null; // Close the connection
?>
