<?php 
session_start();
unset($_SESSION['uid']);
unset($_SESSION['pwd']);
unset($_SESSION['full_name']);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>