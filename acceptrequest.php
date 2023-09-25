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
$profil = $_GET['profile_id'];

    // Prepare the INSERT statement
    $sql = "UPDATE friends SET prosnja_sprejeta = 1 WHERE user_id = ? AND requester_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$uporabnik, $profil])) { // Pass parameters as an array
        setcookie('prijava', "Prošnja sprejeta!");
        setcookie('good', 1);
        if(isUserAdmin($conn)){
            header('Location: profiles_admin.php?id='.$profil.'');
        }
        else{
            header('Location: profiles.php?id='.$profil.'');
        }
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
        
        if ($result == false) {
          return false;
        } else {
          return true;
        }
      } 
    
   
$conn = null; // Close the connection
?>
