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

// Prepare the UPDATE statement
$sql = "UPDATE friends SET prosnja_sprejeta = 1 WHERE user_id = ? AND requester_id = ?";
$stmt = $conn->prepare($sql);

// Check if the statement executed successfully
if ($stmt->execute([$uporabnik, $profil])) {
    setcookie('prijava', "Prošnja sprejeta!");
    setcookie('good', 1);

    // Determine the destination URL based on user's admin status
    $destination = isUserAdmin($conn) ? 'profiles_admin.php?id=' . $profil : 'profiles.php?id=' . $profil;

    header('Location: ' . $destination);
    exit();
} else {
    setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
    setcookie('error', 1);
    header('Location: friends.php');
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
