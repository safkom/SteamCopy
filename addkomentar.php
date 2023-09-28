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

// Use prepared statements to prevent SQL injection
$sql = "INSERT INTO komentarji (text, profil_id, pisatelj_id) VALUES (:text, :profile_id, :uporabnik)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':text', $text, PDO::PARAM_STR);
$stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
$stmt->bindParam(':uporabnik', $uporabnik, PDO::PARAM_INT);

if ($stmt->execute()) {
    setcookie('prijava', "Komentar oddano!");
    setcookie('good', 1);
    header('Location: profiles.php?id=' . $profile_id);
    exit();
} else {
    setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}

$conn = null; // Close the connection
?>
