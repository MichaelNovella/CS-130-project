<!DOCTYPE html>
<html>
    <head>
        <title>
    </head>
    <body>
        <header>
            <nav>
                <a>
                    <img src="img/fresno-state-bulldogs.svg" alt="fresnostate logo">
                </a>
                <ul>
                    <li><a href="main.php">Main</a></li>
                    <li><a href="help.php">Help</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
                <div>
                    <form action="login.php" method="post">
                        <input type="text" name="userid" placeholder="Username or email">
                        <input type="text" name="passd" placeholder="Password">
                        <button type="submit" name="login-pressed">press to Login</button>
                    </form>
                    <a href="signup.php">Signup</a>
                    <form action="signout.php" method="post">
                        <button type="submit" name="logout-pressed">Logout</button>
                    </form>
                </div>
            </nav>
        </header>
    </body>
</html>