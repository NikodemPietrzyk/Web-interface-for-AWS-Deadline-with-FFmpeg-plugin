<?php


if ($path === "/registration"){ ?>
    <script src="js/registrationForm.js"> </script>
<?php }

if ($path === "/login"){ ?>
      <script src="js/loginForm.js"> </script>
  <?php }

if ($path === "/resetPassword"){ ?>
      <script src="js/resetPasswordForm.js"> </script>
  <?php }

if ($path === "/jobForm"){ ?>
      <script src="js/jobForm.js"> </script>
<?php } 

if(!is_logged_in()){ ?>
      <script src="js/loginModal.js"> </script>
<?php }


if ($path === "/directory"){ ?>
      <script src="js/directory.js"> </script>
<?php }
      
if ($path === "/presets" || $path === "/presetsGlobal"){ ?>
      <script src="js/managePresets.js"> </script>
<?php }

if ($path === "/manageUsers"){ ?>
      <script src="js/manageUsers.js"> </script>
<?php }

if ($path === "/jobHistory"){ ?>
      <script src="js/jobHistory.js"> </script>
<?php }

if ($path === "/userSettings"){ ?>
      <script src="js/userSettings.js"> </script>
<?php }


?>


