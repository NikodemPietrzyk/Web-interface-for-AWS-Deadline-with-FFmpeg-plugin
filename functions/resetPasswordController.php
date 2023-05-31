<?php
include_once "connection.php";
require_once "mailer.php";
function findUserByEmail($email){
    $db_handle = new DBController();
    $query = "SELECT password, ID, type, status FROM user WHERE email = '" . $email . "'";
    $dbuser=$db_handle->runQuery($query);
    unset($db_handle);
    return $dbuser[0];
}

function updatePassowrd($userId, $password){
    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    $updateQuery = "UPDATE user SET password = '$password' WHERE ID = '$userId'";
    $db_handle = new DBController();
    $db_handle->updateQuery($updateQuery);   
    unset($db_handle);
    return true;
}

function isPasswordValid($password){
    $validatePassword = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,254}$/m'; 
    if(preg_match($validatePassword,$password)){
        return true;
    }else{
        return false;
    }
}

function verifyPassword($userId,$password){
    $db_handle = new DBController();
    $query = "SELECT password FROM user WHERE ID = '" . $userId . "'";
    $user=$db_handle->runQuery($query);   
    unset($db_handle);
    if(password_verify($password,$user[0]["password"])){
        return true;
    }
    return false;
}


if($_SERVER['REQUEST_METHOD'] == "POST"){
  if(isset($_POST['user_id'])){
    $userId = $_POST['user_id'];
    $password = $_POST['password2'];

    if(isset($userId) && isPasswordValid($password)){
        $db_handle = new DBController();
        $query = "SELECT * FROM user WHERE ID = '" . $userId . "'";
        $user=$db_handle->runQuery($query);   
        unset($db_handle);
    }
    if(!empty($user) && $user[0]['status']==1){
        updatePassowrd($userId, $password);
        $deleteQuery = "DELETE FROM password_resets WHERE user_id = '$userId'";
        $db_handle = new DBController();
        $db_handle->deleteQuery($deleteQuery); 
        session_start();  
        $_SESSION["user_id"]=$user[0]["ID"];
        $_SESSION["email"]=$user[0]["email"];
        $_SESSION["type"]=$user[0]["type"];
        header('Location: /home' );
        die();
    }else{
        echo '<script>alert("User disabled")</script>';
    }
    }
    if(isset($_POST['email'])){
        $token = uniqid();
        $email = $_POST['email'];
    
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $userId = findUserByEmail($email)['ID'];
        }

        if(!empty($userId)){
            $db_handle = new DBController();
            $insert = "INSERT INTO password_resets (email, token, user_id) VALUES ('$email', '$token', '$userId')";
            $db_handle->insertQuery($insert);
            unset($db_handle);
            $reset_link = "/resetPassword?email=$email&token=$token";
            $subject = 'Reset your password'; // Email subject
            sendResetPasswordMail($email, $subject, $reset_link);
            echo '<script>alert("Mail sent")</script>';
        }else{
            echo '<script>alert("No user with matchin email")</script>';
        }
    }
    if(is_logged_in() && !isset($_POST['email'])){
        $userId = $_SESSION['user_id'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        if(verifyPassword($userId,$password)){
            if(isPasswordValid($password2)){
                updatePassowrd($userId, $password2);
            }else{
                echo '<script>alert("Invalid new password")</script>';
            }
        }else{
            echo '<script>alert("Wrong password")</script>';
        }
        echo '<script>alert("Password changed")</script>';
    }
}


?>