<?php
require_once 'connect.php';
session_start();

// Validate and sanitize user input
$uporabnik = $_SESSION['id'];
$komentar_id = $_GET['id'];

// Prepare the DELETE statement
$sql = "DELETE FROM komentarji WHERE id = ? AND pisatelj_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt->execute([$komentar_id, $uporabnik])) { // Pass parameters as an array
    setcookie('prijava', "Komentar izbrisan!");
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
