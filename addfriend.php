<?php
require_once 'connect.php';
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Tu nimaš dostopa.");
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}

// Validate and sanitize user input
$uporabnik = $_SESSION['id'];
$profil = $_GET['profile_id'];

// Prepare the INSERT statement
$sql = "INSERT INTO friends (requester_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

// Check if the statement executed successfully
if ($stmt->execute([$uporabnik, $profil])) {
    setcookie('prijava', "Prošnja za prijateljstvo poslana!");
    setcookie('good', 1);

    // Use the session variable for 'lastlocation' directly in the header
    header('Location: ' . $_SESSION['lastlocation']);
    exit();
} else {
    setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
    setcookie('error', 1);

    // Use the session variable for 'lastlocation' directly in the header
    header('Location: ' . $_SESSION['lastlocation']);
    exit();
}

function isUserAdmin($conn) {
    $sql = "SELECT * FROM uporabniki WHERE id = ? AND admin = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result !== false; // Simplify the return logic
}

$conn = null; // Close the connection
?>
