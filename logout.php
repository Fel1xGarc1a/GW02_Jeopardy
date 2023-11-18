<?php session_start(); /* Starts the session */
session_destroy(); /* Destroy started session */
echo 'You have cleaned session';
header("location:login.php");
exit;
?>
