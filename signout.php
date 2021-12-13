<?php
//these destroy the session and logs the user out of the system
session_start();
session_unset();
session_destroy();
header("Location: index.php");
?>
