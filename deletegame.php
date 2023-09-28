<?php
require_once 'connect.php';
session_start();
$isAdmin = isUserAdmin($conn);
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Tu nimaš dostopa.");
    setcookie('error', 1);
    header('Location: index.php');
    exit();
}

// Validate and sanitize user input
$igra_id = $_GET['id'];

    // Prepare the INSERT statement
    $sql = "DELETE FROM igre WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$igra_id])) { // Pass parameters as an array
        setcookie('prijava', "Igra izbrisana!");
        setcookie('good', 1);
        if($isAdmin){
            header('Location: admin_library.php');
        }
        else{
            header('Location: library.php');
        }
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: library.php');
        exit();
    }

    function isUserAdmin($conn) {
        $sql = "SELECT * FROM uporabniki WHERE id = ? AND admin = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result == false) {
          return false;
        } else {
          return true;
        }
      }
$conn = null; // Close the connection
?>
