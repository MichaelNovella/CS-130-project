<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- This is the style guide linked outside the file-->
    <link rel="stylesheet" href="external.css"> 
    </head>
    <body>
        <header>
            <ul class="navbar">
            <li> <a href="index.php">
            <img src="img/fresno-state-bulldogs.png"  class="logo-image" alt="fresnostate logo">
                </a></li>
                    <li><a href="index.php">Main</a></li>
                    <li><a href="help.php">Help</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <?php
                    if (isset($_SESSION['userid'])) {
                        echo '<li><a href="leaderboard.php">Leaderboards</a></li><li><form action="signout.php" method="post">
                        <button type="submit" name="logout-pressed">Logout</button>
                    </form></li>';
                    }
                    else{
                        echo '<li><form action="login.php" method="post">
                        <input type="text" name="userid" placeholder="Username or email">
                        <input type="text" name="passd" placeholder="Password">
                        <button type="submit" name="login-pressed">press to Login</button>
                    </form></li>
                    <li><a href="signup.php">Signup</a></li>';
                    }
                    ?>
                    
                    
                
            </ul>
        </header>
    <!-- </body>
</html> -->