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
$igra_id = $_GET['id'];
$text = $_POST['mnenje'];
//preveri če je bilo mnenje pozitivno ali negativno
if($_POST['ocena'] == 1){
    $ocena = 1;
}
else{
    $ocena = 0;
}

    // Prepare the INSERT statement
    $sql = "INSERT INTO mnenja (uporabnik_id, igra_id, text, ocena)
    VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$uporabnik, $igra_id, $text, $ocena])) { // Pass parameters as an array
        setcookie('prijava', "Mnenje oddano!");
        setcookie('good', 1);
        header('Location: gamepage.php?id='.$igra_id.'');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: index.php');
        exit();
    }
$conn = null; // Close the connection
?>
