<?php


if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //include("functions.php");
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
      $validatePassword = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,254}$/m'; 
      $validateName = '/^.{2,254}$/';    
      $firstname = $_POST['firstname'];
      $surname = $_POST['surname'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $role = 1;
      $status = 0;
        if(preg_match($validateName,$firstname) && preg_match($validateName,$surname) && filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match($validatePassword,$password))//TO DO
        {
	        $db_handle = new DBController();
	        $query = "SELECT * FROM user WHERE email = '" . $email . "'";
	        $count = $db_handle->numRows($query);
            if($count==0) {
              $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
              if($email == MAINTAINER || $email == IT_MAIL){  
                $role = 2;
              }
              $query = "INSERT INTO user(name,surname,email,password,type,status) VALUES('$firstname','$surname','$email','$password','$role','$status')";
              $current_id = $db_handle->insertQuery($query);
              if(!empty($current_id)) {
                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"."activate.php?id=" . $current_id;
                $subject = "User Registration Activation Email";
                $content = "Click this link to activate your account. <a href='" . $actual_link . "'>" . $actual_link . "</a>";
                $mailHeaders = "From: Clinic\r\n";
                if(mail($email, $subject, $content, $mailHeaders)) {
                  $message = "You have registered and the activation mail is sent to your email. Click the activation link to activate you account.";	
                  $type = "success";
                }else{
                  echo "Could not send email";
                }
              }else{
              echo "Could not insert user's data";
              }
            }else{
              echo "Email is already in use";
            }
          
          $_SESSION['user_id'] = $user_id;
          header("Location: ../home");
          //die;
        }else{
            echo "Submitted data is incorrect";
        }
    }
}

?>