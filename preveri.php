<?php
require_once 'connect.php';
session_start();
$email = $_GET['email'];
$password = $_GET['geslo'];

$sql = "SELECT * FROM uporabniki WHERE email = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hash = $row['geslo'];
    if (password_verify($password, $hash)) {
        if ($row['admin'] == 1) {
            $_SESSION["id"] = $row['id'];
            $_SESSION["admin"] = 1;
            setcookie('prijava', "Prijava uspešna.");
            setcookie('good', 1);
            header('Location: admin.php');
        } else {
            $_SESSION["id"] = $row['id'];
            setcookie('prijava', "Prijava uspešna.");
            setcookie('good', 1);
            header('Location: main.php');
        }
    } else {
        setcookie('prijava', "Napačno geslo.");
        setcookie('error', 1);
        header('Location: index.php');
    }
} else {
    setcookie('prijava', "Uporabnik z tem mailom ne obstaja.");
    setcookie('error', 1);
    header('Location: index.php');
}

$stmt->close();
$conn->close();
?>
