<?php
require_once 'connect.php';
session_start();

// Validate and sanitize user input
$ime = $_POST['ime'];
$priimek = $_POST['priimek'];
$username = $_POST['username'];
$mail = $_POST['email'];
$geslo1 = $_POST['geslo'];
$geslo = password_hash($geslo1, PASSWORD_DEFAULT);

// Prepare the SELECT statement
$sql = "SELECT * FROM uporabniki WHERE mail = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$mail]); // Pass parameters as an array

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result) == 0) {
    // Prepare the INSERT statement
    $sql = "INSERT INTO uporabniki (ime, priimek, mail, geslo, username)
    VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$ime, $priimek, $mail, $geslo, $username])) { // Pass parameters as an array
        setcookie('prijava', "Registracija uspešna. Prijavite se z vnešenimi podatki.");
        setcookie('good', 1);
        header('Location: index.php');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: registracija.php');
        exit();
    }
} else {
    setcookie('prijava', "Uporabnik z tem mailom že obstaja.");
    setcookie('warning', 1);
    header('Location: registracija.php');
    exit();
}

$conn = null; // Close the connection
?>
