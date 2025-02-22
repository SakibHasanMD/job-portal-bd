<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location: ../View/login.php");
exit();
?>