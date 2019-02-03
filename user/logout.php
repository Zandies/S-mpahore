<?php
session_start();
setcookie("remember");
session_destroy();
header('Location: ../index.php');
?>
