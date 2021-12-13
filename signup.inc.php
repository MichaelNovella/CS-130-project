<?php

//this is to show that the user needs to submit it and not stumble upon this page
if(isset($_POST["signupsub"])){
    require 'helperfunctions.php';

    $username = $_POST["username"]; //these are the variables that will be inserted into the table
    $email = $_POST["useremail"];
    $pwd = $_POST["passwrd"];
    $pwdver = $_POST["verify-passwrd"];
    //these are error checks that can be get from the index or signup page, currently for future debugging
    if (empty($username) || empty($email)|| empty($pwd)|| empty($pwdver)) {
        header("Location: signup.php?error=emptyfield&username=".$username."&useremail=".$email); //something was missing
        exit();
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/",$username)) {
        header("Location: signup.php?error=invalididendity");//special charaters not allowed
        exit();
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.php?error=invalidmain&username=".$username);//it wasn't a proper email sent
        exit();
    }
    elseif (!preg_match("/^[a-zA-Z0-9]*$/",$username)) {
        header("Location: signup.php?error=invalidid&email=".$email);//only nonspecial codes required for username
        exit();
    }
    elseif ($pwd !== $pwdver) {
        header("Location: signup.php?error=passwordnotmatch&username=".$username."&useremail=".$email); //passwords don't match
        exit();
    }
    else {
        $sql = "SELECT usernamesid FROM users WHERE usernamesid=?";//this is a check to see that each user is unique
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $results = $stmt->num_rows;
        if ($results >0 ) {
            header("Location: signup.php?error=usertaken&useremail=".$email);//this shows that the username is already taken
            exit();
        }
        else {
            $sql = "INSERT INTO  users (usernamesid,email_id,pwdusers) VALUES(?,?,?)";//this is preping the table to insert the passed in values to the table
            $hasspasse = password_hash($pwd, PASSWORD_DEFAULT); //hashes the password for security
            $stmt = $conn->prepare($sql); //preps it
            $stmt->bind_param("sss", $username,$email,$hasspasse);//sends everything to the table
            $stmt->execute();
            //this creates a dummy user from the new user to the leaderboard for being your first time
            $sql ="INSERT INTO userboard (ids,turns,score,gameswon,games,duration) VALUES (?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $z = 0;
                $stmt->bind_param("siiiii", $username,$z,$z,$z,$z,$z);
                $stmt->execute();
            header("Location: signup.php?signup=passed");//this is confirm to the user to sign in with their new login credentials
            exit();
        }
        
    }
    
    $stmt->close();//standard close
    $conn->close();

}
else {
    header("Location: signup.php");//not let people into this include file
    exit();
}