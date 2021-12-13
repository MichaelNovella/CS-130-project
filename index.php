<?php
require "heading-nav.php";

?>
<!-- This is the index class that will display a message that the user needs to sign in to access the game -->
        <div class="container">
        <?php
        if (isset($_SESSION['userid'])) {
            include 'Gomoku_WIP.php';
        }
        else{ 
            // a condition that can direct the user to what they can do
            echo '<p>You have to log in to play the game or <a href="signup.php">Sign-up</a></p>';
        }
        ?>
        </div>
    </body>
</html> 
