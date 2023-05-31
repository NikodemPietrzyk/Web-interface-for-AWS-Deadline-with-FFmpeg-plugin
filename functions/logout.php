<?php
include_once "authentication.php";
session_start(); 
logout();
header('Location: /home');
?>