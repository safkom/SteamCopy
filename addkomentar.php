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
$profile_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Ensure $profile_id is an integer
$text = htmlspecialchars($_POST['mnenje'], ENT_QUOTES, 'UTF-8');

// Use prepared statements to prevent SQL injection
$sql = "INSERT INTO komentarji (text, profil_id, pisatelj_id) VALUES (:text, :profile_id, :uporabnik)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':text', $text, PDO::PARAM_STR);
$stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);
$stmt->bindParam(':uporabnik', $uporabnik, PDO::PARAM_INT);

if ($stmt->execute()) {
    setcookie('prijava', "Komentar oddano!");
    setcookie('good', 1);
} else {
    setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
    setcookie('error', 1);
}

// Redirect to the previous page
if (isset($_SESSION['lastlocation'])) {
    header('Location: ' . $_SESSION['lastlocation']);
} else {
    header('Location: index.php');
}
exit();
?>
