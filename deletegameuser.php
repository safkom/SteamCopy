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
$igra_id = $_GET['id'];

    // Prepare the INSERT statement
    $sql = "DELETE FROM nakupi WHERE uporabnik_id = ? AND igra_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$uporabnik, $igra_id])) { // Pass parameters as an array
        setcookie('prijava', "Igra izbrisana!");
        setcookie('good', 1);
        header('Location: '. $_SESSION['lastlocation'] .'');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: '. $_SESSION['lastlocation'] .'');
        exit();
    }
$conn = null; // Close the connection
?>
