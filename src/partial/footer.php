<footer>
<?php if(!is_logged_in()){?>
    <section class="modal-container" id="modal">
        <section class="modal">
          <button class="close-btn" id="close">x</button>
          <h2>Sign In</h2>
            <form method="post" action="functions/loginValidate.php" class="form-login">
              <div class="form-control">
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="Enter Email"/>
              </div>
              <div class="form-control">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Enter Password"/>
              </div>
              <div class="toggle-logged-container">
                <p>Keep me logged in:</p>
                <label class="toggle-logged"> <input type="checkbox" name="logged"> <span class="slider"></span> </label>
              </div>
              <input type="submit" value="Submit" class="login-btn" />
            </form>
          <a class="forgotPassword" href="resetPassword">Forgot Password?</a>
        </section>
    </section>
<?php } ?>
</div>
</footer>
<script src="js/navbar.js"></script>

</body>

<?php
include "functions/addJS.php";
?>
</html>




            
