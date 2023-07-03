<?php
session_start();
include_once "functions/connection.php";
require_once "functions/authentication.php";


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="shortcut icon" href="/css/images/favicon-16x16.png">
<?php
include_once "functions/addCSS.php";
?>   
    <title>Converter</title>
  </head>
  <body>
 <nav class="navbar">
        <div class="brand-title">Converter</div>
        <a href="#" class="toggle-button">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </a>
        <div class="navbar-links">
          <ul>
            <li><a href="/">Home</a></li>
            <!-- <li><a href="/phpRESTTEST.php">Contact</a></li>  -->
        <?php if(is_logged_in()){?>
            <li><a href="directory">Directory</a></li> 
            <li><div class="dropdown">
              <button class="dropbtn">Render</button>
              <div class="dropdown-content">
                <a href="jobForm">Submitter</a> 
                <a href="jobHistory">History</a>
              </div>
            </div></li>
            <li><div class="dropdown">
              <button class="dropbtn">User</button>
              <div class="dropdown-content">
                <a href="presets">Presets</a>  
                <a href="userSettings">Settings</a>
              </div>
            </div></li>            
        <?php if(is_admin()){?>
            <li><div class="dropdown">
              <button class="dropbtn">Admin</button>
              <div class="dropdown-content">
                <a href="presetsGlobal">Global Presets</a>     
                <a href="manageUsers">Manage Users</a>
              </div>
            </div></li> 
            
        <?php }}?>
        <?php if(!is_logged_in()){?>
            <li><a href="registration">Sign Up</a></li>
            <li><a href="#"  id="open">Sign In</a></li>
        <?php }else{ ?>
            <li><a href="functions/logout.php">Log Out</a></li>
        <?php }?>
          </ul>
        </div>
      </nav>
      