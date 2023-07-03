<?php


if ($path === "/registration"){ ?>
    <link rel="stylesheet" href="css/registration.css" />
<?php }

if ($path === "/login" || $path === "/resetPassword"){ ?>
    <link rel="stylesheet" href="css/login.css" />
<?php }

if ($path === "/directory"){ ?>
    <link rel="stylesheet" href="css/directory.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php }


if ($path === "/jobForm"){ ?>
      <link rel="stylesheet" href="css/jobForm.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php } 

if ($path === "/presets" || $path === "/presetsGlobal"){ ?>
    <link rel="stylesheet" href="css/presets.css" />
<?php }
  
if ($path === "/jobHistory"){ ?>
    <link rel="stylesheet" href="css/jobHistory.css" />
<?php }

if ($path === "/manageUsers"){ ?>
    <link rel="stylesheet" href="css/manageUsers.css" />
<?php }

if ($path === "/userSettings"){ ?>
    <link rel="stylesheet" href="css/userSettings.css" />
<?php }

?>


