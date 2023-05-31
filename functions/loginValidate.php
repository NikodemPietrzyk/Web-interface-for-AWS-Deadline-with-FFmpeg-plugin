<?php
include_once "connection.php";
include_once "authentication.php";
if($_SERVER['REQUEST_METHOD'] == "POST")
  {
  $email = $_POST['email'];
  $password = $_POST['password'];
  
  $db_handle = new DBController();
  $query = "SELECT password, ID, type, status FROM user WHERE email = '" . $email . "'";
  $dbuser=$db_handle->runQuery($query);
  unset($db_handle);
  if(($dbuser[0]["status"])==0){
    echo '<script>alert("User disabled")</script>';
  }
    if(password_verify($password,$dbuser[0]["password"]) ){
      if($_POST['logged']){
        remember_me($dbuser[0]["ID"]); 
        session_start();
        $_SESSION["user_id"]=$dbuser[0]["ID"];
        $_SESSION["email"]=$email;
        $_SESSION["type"]=$dbuser[0]["type"];
        header('Location: ' . $_SERVER["HTTP_REFERER"] );
        die();
      }
        session_start(); 
        $_SESSION["user_id"]=$dbuser[0]["ID"]; 
        $_SESSION["email"]=$email;
        $_SESSION["type"]=$dbuser[0]["type"];
        header('Location: ' . $_SERVER["HTTP_REFERER"] );
        die();
    }else{
      header('Location: /login?failed=true' );
    }
  } 

?>