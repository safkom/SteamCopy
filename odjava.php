<?php
session_start();

if (isset($_COOKIE['banned'])) {
    // User is banned, show a message or redirect to a ban page
    setcookie('prijava', "Vaš račun je blokiran.");
    setcookie('error', 1);
    setcookie('banned', "", time() - 3600);
    header('Location: index.php'); // Replace 'ban_page.php' with the actual ban page
    exit();
}

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
