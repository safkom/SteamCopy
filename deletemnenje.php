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
$mnenje_id = $_GET['id'];

    // Prepare the INSERT statement
    $sql = "DELETE FROM mnenja WHERE id = ? AND uporabnik_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$mnenje_id, $uporabnik])) { // Pass parameters as an array
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
$conn = null; // Close the connection
?>
