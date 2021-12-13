<?php

// <input type="text" name="username" placeholder="Username">
// </div>
// </div>
// <div class="row">
// <div class="col-75">
//     <input type="text" name="useremail" placeholder="Email">
// </div>
// </div>
// <div class="row">
// <div class="col-75">
//     <input type="password" name="passwrd" placeholder="Password">
// </div>
// </div>
// <div class="row">
// <div class="col-75">
//     <input type="password" name="verify-passwrd" placeholder="Verify Password">
// </div>
// </div>
// <div class="row">
// <div class="col-75">
//     <button type="submit" name="signupsub">Signup</button>
// </div>
// </div>
if(isset($_POST["signupsub"])){
    require 'helperfunctions.php';

    $username = $_POST["username"];
    $email = $_POST["useremail"];
    $pwd = $_POST["passwrd"];
    $pwdver = $_POST["verify-passwrd"];

    if (empty($username) || empty($email)|| empty($pwd)|| empty($pwdver)) {
        header("Location: signup.php?error=emptyfield&username=".$username."&useremail=".$email);
        exit();
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/",$username)) {
        header("Location: signup.php?error=invalididendity");
        exit();
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.php?error=invalidmain&username=".$username);
        exit();
    }
    elseif (!preg_match("/^[a-zA-Z0-9]*$/",$username)) {
        header("Location: signup.php?error=invalidid&email=".$email);
        exit();
    }
    elseif ($pwd !== $pwdver) {
        header("Location: signup.php?error=passwordnotmatch&username=".$username."&useremail=".$email);
        exit();
    }
    else {
        $sql = "SELECT usernamesid FROM users WHERE usernamesid=?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $results = $stmt->num_rows;
        if ($results >0 ) {
            header("Location: signup.php?error=usertaken&useremail=".$email);
            exit();
        }
        else {
            $sql = "INSERT INTO  users (usernamesid,email_id,pwdusers) VALUES(?,?,?)";
            $hasspasse = password_hash($pwd, PASSWORD_DEFAULT);
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("sss", $username,$email,$hasspasse);
            $stmt->execute();
            $sql ="INSERT INTO userboard (ids,turns,score,gameswon,games,duration) VALUES (?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $z = 0;
                $stmt->bind_param("siiiii", $username,$z,$z,$z,$z,$z);
                $stmt->execute();
            header("Location: signup.php?signup=passed");
            exit();
        }
        
    }
    
    $stmt->close();
    $conn->close();

}
else {
    header("Location: signup.php");
    exit();
}