<?php
// this is the login helper page that the top nav bar will use to sign in the user
if (isset($_POST['login-pressed'])) { //this means it required the user to have pressed the login button and not stumble upon this page
    require 'helperfunctions.php';
    $mailuid = $_POST['userid']; //these gets are for inserting into the users table
    $pass = $_POST['passd'];
    //these next few sets are for error checking in the index page, these will be seen in the index page as errors the user has made
    if (empty($mailuid) || empty($pass)) {
        header("Location: index.php?error=emptyfield");
    exit();
    }
    else{
        //this is where we send in the inputted data to compare to a similar data inside the table
        $sql = "SELECT * FROM users WHERE usernamesid=? OR email_id=?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$mailuid,$mailuid);
        $stmt->execute();
        //this is to get a row from the table
        $resukj = $stmt->get_result();
        if ($row = $resukj->fetch_assoc()) {
            $passcheck = password_verify($pass,$row['pwdusers']); //this verifies that the password sent to here is the same as the one in the table
            if ($passcheck == false) { //this is if the user gets it wrong
                header("Location: index.php?error=wrongpass");
            exit();//these exits are there for the purpose of not completing the rest of this file since it will end the connection to the server
            }
            elseif($passcheck == true){
                session_start();//this is to start the session that will carry on throughout the whole site
                $_SESSION['userid']= $row['idUser'];
                $_SESSION['usernameloggedin']= $row['usernamesid'];

                // $sql ="INSERT INTO userboard (ids,turns,score,gameswon,games,duration) VALUES (?,?,?,?,?,?)";
                // $stmt = $conn->prepare($sql);
                // $z = 0;
                // $stmt->bind_param("siiiii", $row['usernamesid'],$z,$z,$z,$z,$z);
                // $stmt->execute();
                header("Location: index.php?signing=pass");
            exit();
            }
            else{
                header("Location: index.php?error=wrongpass"); //this is for entering the wrong password
            exit();
            }
        }
        else {
            header("Location: index.php?error=nouser");
            exit();
        }
    }
}
else {
    header("Location: index.php");
    exit();
}
?>