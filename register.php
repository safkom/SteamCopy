<?php
require_once 'connect.php';
session_start();

$ime = $_GET['ime'];
$priimek = $_GET['priimek'];
$mail = $_GET['email'];
$geslo1 = $_GET['geslo'];
$geslo = password_hash($geslo1, PASSWORD_DEFAULT);
$naslov = $_GET['naslov'];
$kraj = $_GET['kraj'];

// Prepare the SELECT statement
$sql = "SELECT * FROM uporabniki WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $mail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Prepare the INSERT statement
    $sql = "INSERT INTO uporabniki (ime, priimek, email, geslo, naslov, kraj_id)
    VALUES (?, ?, ?, ?, ?, (SELECT id from kraji WHERE kraj = ?))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $ime, $priimek, $mail, $geslo, $naslov, $kraj);
    if ($stmt->execute()) {
        setcookie('prijava', "Registracija uspešna. Prijavite se z vnešenimi podatki.");
        setcookie('good', 1);
        header('Location: index.php');
        exit();
    } else {
        setcookie('prijava', "Error: " . $stmt->error);
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

$stmt->close();
$conn->close();
?>
