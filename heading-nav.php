<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="external.css">
    <!-- <style>
        .logo-image{
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    margin-top: -6px;
}
/* ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
    position: fixed;
    top: 0;
    width: 100%;
  }
  
  li {
    float: left;
  }
  
  li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
  }
  
  li a:hover:not(.active) {
    background-color: #111;
  }
  
  .active {
    background-color: #04AA6D;
  } */
  ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}

li {
  float: left;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover:not(.active) {
  background-color: #111;
}

.active {
  background-color: #04AA6D;
}
        </style> -->
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
    </body>
</html>