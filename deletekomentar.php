<?php
require_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Za uporabo te strani, se rabiš prijaviti.");
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}

// Validate and sanitize user input
$uporabnik = $_SESSION['id'];
$komentar_id = $_GET['id'];

    // Prepare the INSERT statement
    $sql = "DELETE FROM komentarji WHERE id = ? AND pisatelj_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$komentar_id, $uporabnik])) { // Pass parameters as an array
        setcookie('prijava', "Komentar izbrisano!");
        setcookie('good', 1);
        header('Location: index.php');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: index.php');
        exit();
    }
$conn = null; // Close the connection
?>
