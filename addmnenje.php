<?php
require_once 'connect.php';
session_start();

if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Tu nimaš dostopa.");
    setcookie('error', 1);
    header('Location: login.php');
    exit();
}

// Validate and sanitize user input
$uporabnik = $_SESSION['id'];
$igra_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Ensure $igra_id is an integer
$text = htmlspecialchars($_POST['mnenje'], ENT_QUOTES, 'UTF-8');

// Validate the 'ocena' input to ensure it's either 0 or 1
$ocena = ($_POST['ocena'] == 1) ? 1 : 0;

// Use prepared statements to prevent SQL injection
$sql = "INSERT INTO mnenja (uporabnik_id, igra_id, text, ocena)
        VALUES (:uporabnik, :igra_id, :text, :ocena)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':uporabnik', $uporabnik, PDO::PARAM_INT);
$stmt->bindParam(':igra_id', $igra_id, PDO::PARAM_INT);
$stmt->bindParam(':text', $text, PDO::PARAM_STR);
$stmt->bindParam(':ocena', $ocena, PDO::PARAM_INT);

if ($stmt->execute()) {
    setcookie('prijava', "Mnenje oddano!");
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
