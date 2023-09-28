<?php
session_start();
if (!isset($_SESSION['id'])) {
    setcookie('prijava', "Opla. Zakaj bi se odjavil, če pa nisi prijavljen?");
    setcookie('warning', 1);
    header('Location: index.php');
    exit();
}
// User is not banned, perform logout
session_destroy();
// Delete cookies
setcookie('prijava', "Odjava uspešna.");
setcookie('good', "", time() - 3600);
header('Location: index.php'); // Redirect to the index page after successful logout
exit();
?>
