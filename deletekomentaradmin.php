<?php
require_once 'connect.php';
session_start();

function isUserAdmin($conn) {
    $sql = "SELECT * FROM uporabniki WHERE id = ? AND admin = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result !== false;
}

if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Za uporabo te strani, se rabiš prijaviti.");
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}

// Check if the user is an admin
$isAdmin = isUserAdmin($conn);
if (!$isAdmin) {
    setcookie('prijava', "Za uporabo te strani, nimaš pravic.");
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}

// Validate and sanitize user input
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $komentar_id = $_GET['id'];

    // Prepare the DELETE statement
    $sql = "DELETE FROM komentarji WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$komentar_id])) { // Pass parameters as an array
        setcookie('prijava', "Komentar izbrisan!");
        setcookie('good', 1);
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
    }
} else {
    setcookie('prijava', "Invalid comment ID.");
    setcookie('error', 1);
}

header('Location: index.php');
exit();
?>
