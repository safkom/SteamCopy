<?php
if(!isset($_SESSION['id'])){
    setcookie('prijava', "Opla. Zakaj bi se odjavil, če pa nisi prijavljen?");
    setcookie('warning', 1);
    header('Location: index.php');
    exit();
  }
session_start();
session_destroy();
//delete cookie id and prijava
setcookie('prijava', "Odjava uspešna.");
setcookie('good',"", time()- 3600);
header('Location: index.php');
?>