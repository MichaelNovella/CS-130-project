<?php
require "heading-nav.php";

?>
<!-- <!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="external.css">
    </head>
    <body> -->
        <div class="container">
        <?php
        if (isset($_SESSION['userid'])) {
            include 'Gomoku_WIP.php';
        }
        else{
            echo '<p>You have to log in</p>';
        }
        ?>
        </div>
    </body>
</html> 
