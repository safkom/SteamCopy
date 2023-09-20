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
$igra_id = $_GET['id'];

    // Prepare the INSERT statement
    $sql = "DELETE FROM igre WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$igra_id])) { // Pass parameters as an array
        setcookie('prijava', "Igra izbrisana!");
        setcookie('good', 1);
        header('Location: library.php');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: library.php');
        exit();
    }
$conn = null; // Close the connection
?>
