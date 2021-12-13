<?php
require "heading-nav.php";
?>
 <!-- This is to help with signing up for the service  -->
        <div class="container">
            <form action="signup.inc.php" method="post">
            <div class="row">
                <h1>Sign Up</h1>
                <div class="col-75">
                    <input type="text" name="username" placeholder="Username">
                </div>
            </div>
            <div class="row">
                <div class="col-75">
                    <input type="text" name="useremail" placeholder="Email">
                </div>
            </div>
            <div class="row">
                <div class="col-75">
                    <input type="password" name="passwrd" placeholder="Password">
                </div>
            </div>
            <div class="row">
                <div class="col-75">
                    <input type="password" name="verify-passwrd" placeholder="Verify Password">
                </div>
            </div>
            <div class="row">
                <div class="col-75">
                    <button type="submit" name="signupsub">Signup</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html> 
