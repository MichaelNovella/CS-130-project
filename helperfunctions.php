<?php
$servername = "localhost"; // default server name
$username = "AdminLab12"; // user name that you created
$password = "4VPnroTOC6wOU3mn"; // password that you created
$dbname = "myDB"; //set this to be the table it will read from and what info is needed to be returned
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: ". $conn->connect_error);
}
?>