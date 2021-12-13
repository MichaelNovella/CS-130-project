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
$sql = "CREATE TABLE IF NOT EXISTS userboard (
    ids VARCHAR(30),
    turns INT(30),
    score INT(30),
    gameswon INT(6),
    games INT(6),
    duration INT(190) 
    )";
if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully<br>";
} else {
    //echo "Error: " . $sql . "<br>" . $connection->error ."<br>";
}
?>