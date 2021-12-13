<?php
//this is to send the info from the ended game to the server
include "helperfunctions.php";
// turns='+ turn+'&score='+ score+'&duration='+ duration+'&winner'+ winner

// $sql = "CREATE TABLE IF NOT EXISTS userboard (
//     id INT(6) PRIMARY KEY,
//     turns INT(30),
//     score INT(30),
//     gameswon INT(6),
//     games INT(6),
//     duration VARCHAR(190) 
//     )";
// if ($conn->query($sql) === TRUE) {
//     //echo "New record created successfully<br>";
// } else {
//     //echo "Error: " . $sql . "<br>" . $connection->error ."<br>";
// }

$turnss =$_POST['turns'];
$scores =$_POST['score'];
$wongame = $_POST['winner'];
$sql ="SELECT MAX(games) FROM userboard WHERE ids=".$_SESSION['usernameloggedin'];
$result = $conn->query($sql);
$gamesdone = $result + 1;
$durationtime =$_POST['duration'];
$stmt = $conn->prepare("INSERT INTO userboard (ids,turns,score,gameswon,games,duration)  VALUES(?.?,?,?,?,?)");
$stmt->bind_param("siiiii", $_SESSION['usernameloggedin'],$turnss,$scores,$wongame,$gamesdone,$durationtime);
$stmt->execute();
?>