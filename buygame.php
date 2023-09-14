<?php
require_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}
$igra_id = $_GET['id'];
$user_id = $_SESSION['id'];

$sql = "INSERT INTO nakupi (uporabnik_id, igra_id)
    VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$user_id, $igra_id])) { // Pass parameters as an array
        setcookie('prijava', "Igra uspešno kupljena!");
        setcookie('good', 1);
        header('Location: library.php');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: index.php');
        exit();
    }

?>