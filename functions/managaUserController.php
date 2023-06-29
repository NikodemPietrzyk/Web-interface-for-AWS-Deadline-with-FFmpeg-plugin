<?php
require_once "mailer.php";
require_once "connection.php";
function getUserData($id){
    $db_handle = new DBController();
    $query = "SELECT * FROM user WHERE ID = '" . $id . "'";
    $dbuser=$db_handle->runQuery($query);
    unset($db_handle);
    return $dbuser[0];
}



if($_SERVER['REQUEST_METHOD'] == "POST")
  {
    $userId = $_POST['id'];
    $userData = getUserData($userId);
    if($_POST['type']=='Reset'){
        $token = uniqid();
        $email = $userData['email'];
        $db_handle = new DBController();
        $insert = "INSERT INTO password_resets (email, token, user_id) VALUES ('$email', '$token', '$userId')";
        $db_handle->insertQuery($insert);
        unset($db_handle);
        $reset_link = "/resetPassword?email=$email&token=$token";
        $subject = 'Reset your password'; // Email subject
        sendResetPasswordMail($email, $subject, $reset_link);
        echo '<script>alert("Mail sent")</script>';
        header("Refresh:0");
    }
    //hardcoded admin ID
    if($userData['email'] != MAINTAINER && $userData['email'] != IT_MAIL){
        if($_POST['type']=='Active'){
            $db_handle = new DBController();
            $update = "UPDATE user SET status = '0' WHERE ID = '$userId'";
            $db_handle->updateQuery($update);
            unset($db_handle);
            
            header("Refresh:0");
        }
        if($_POST['type']=='Disabled'){
            $email = $userData['email'];
            $db_handle = new DBController();
            $update = "UPDATE user SET status = '1'WHERE ID = '$userId'";
            $db_handle->updateQuery($update);
            unset($db_handle);
            sendActivateUserMail($email);
            header("Refresh:0");
        }
        if($_POST['type']=='Update'){
            print_r($_POST);
            $db_handle = new DBController();
            $type = $_POST['usertype'];
            $priority = $_POST['priority'];
            $update = "UPDATE user SET type = '$type', priority = '$priority' WHERE ID = '$userId'";
            $db_handle->updateQuery($update);
            unset($db_handle);
            header("Refresh:0");
        }
    }
  }
  ?>