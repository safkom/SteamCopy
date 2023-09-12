<?php
require_once 'connect.php';
session_start();
$email = $_POST['email'];
$password = $_POST['geslo'];

$sql = "SELECT * FROM uporabniki WHERE mail = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$email]); // Pass parameters as an array
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result !== false) {
    $hash = $result['geslo'];
    if (password_verify($password, $hash)) {
        // Add the user's username to the session
        $_SESSION["username"] = $result['username'];

        if ($result['admin'] == 1) {
            $_SESSION["id"] = $result['id'];
            $_SESSION["admin"] = 1;
            setcookie('prijava', "Prijava uspešna.");
            setcookie('good', 1);
            header('Location: index.php');
            exit();
        } else {
            $_SESSION["id"] = $result['id'];
            setcookie('prijava', "Prijava uspešna.");
            setcookie('good', 1);
            header('Location: index.php');
            exit();
        }
    } else {
        setcookie('prijava', "Napačno geslo.");
        setcookie('error', 1);
        header('Location: login.php');
        exit();
    }
} else {
    setcookie('prijava', "Uporabnik z tem mailom ne obstaja.");
    setcookie('error', 1);
    header('Location: login.php');
    exit();
}

$conn = null; // Close the connection
?>
