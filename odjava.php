<?php
session_start();
session_destroy();
//delete cookie id and prijava
setcookie('prijava', "Odjava uspešna.");
setcookie('good',"", time()- 3600);
header('Location: index.php');
?>