<?php
require_once 'connect.php';
session_start();
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Za uporabo te strani, se rabiš prijaviti.");
    setcookie('error', 1);
    header('Location: login.php');
    exit();
}
$igra_id = $_GET['id'];
$user_id = $_SESSION['id'];

$sql = "SELECT * FROM uporabniki WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$denar = $result['denar'];

$sql = "SELECT * FROM igre WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$igra_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$cena_igre = $result['cena'];

if($denar < $cena_igre){
    setcookie('prijava', "Nimaš dovolj denarja.");
    setcookie('error', 1);
    header('Location: '. $_SESSION['lastlocation'] .'');
    exit();
}


$sql = "INSERT INTO nakupi (uporabnik_id, igra_id)
    VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$user_id, $igra_id])) { // Pass parameters as an array
        $sql = "UPDATE uporabniki SET denar = denar - ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cena_igre, $user_id]);
        setcookie('prijava', "Igra uspešno kupljena!");
        setcookie('good', 1);
        header('Location: '. $_SESSION['lastlocation'] .'');
        exit();
    } else {
        setcookie('prijava', "Error: " . implode(", ", $stmt->errorInfo()));
        setcookie('error', 1);
        header('Location: '. $_SESSION['lastlocation'] .'');
        exit();
    }

?>